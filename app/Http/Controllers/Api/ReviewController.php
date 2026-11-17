<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DonationTransaction;
use App\Models\Review;
use App\Models\VolunteerApplication;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'activity_type' => 'nullable|in:all,donation,volunteer',
            'activity_id' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);
        $query = Review::query();
        if (!empty($filters['activity_type']) && $filters['activity_type'] !== 'all') {
            $query->where('activity_type', $filters['activity_type']);
        }
        if (!empty($filters['activity_id'])) {
            $query->where('activity_id', $filters['activity_id']);
        }
        $total = (clone $query)->count();
        $average = (float) ((clone $query)->avg('rating') ?? 0);
        $counts = (clone $query)->selectRaw('rating, COUNT(*) AS total')->groupBy('rating')->pluck('total', 'rating');
        $distribution = [];
        foreach (range(1, 5) as $rating) {
            $distribution[$rating] = (int) ($counts[$rating] ?? 0);
        }
        $items = $query->with('user')->latest()->orderByDesc('id')->paginate($filters['per_page'] ?? 20);
        $viewer = $request->user() ?? auth('sanctum')->user();

        return response()->json([
            'data' => $items->getCollection()->map(fn ($review) => $this->serialize($review, $viewer?->id))->values(),
            'summary' => [
                'total_reviews' => $total,
                'average_rating' => round($average, 2),
                'distribution' => (object) $distribution,
            ],
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function eligibility(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $reviews = Review::where('user_id', $userId)->get()->keyBy(fn ($r) => "$r->activity_type:$r->activity_id");
        $activities = collect();
        $donations = DonationTransaction::with('campaign')->where('user_id', $userId)
            ->where('status', 'VERIFIED')->latest()->get();
        foreach ($donations as $donation) {
            if (!$donation->campaign) continue;
            $key = 'donation:'.$donation->campaign_id;
            if ($activities->has($key)) continue;
            $review = $reviews->get($key);
            $activities->put($key, [
                'activity_type' => 'donation',
                'activity_id' => (int) $donation->campaign_id,
                'reference_id' => $donation->id,
                'title' => $donation->campaign->title,
                'subtitle' => 'Donasi terverifikasi',
                'order_id' => $donation->order_id,
                'date' => $donation->created_at?->toDateString() ?? '',
                'has_reviewed' => $review !== null,
                'existing_review' => $review ? $this->serialize($review, $userId) : null,
            ]);
        }
        $applications = VolunteerApplication::with('campaign')->where('user_id', $userId)
            ->where('status', 'approved')->latest()->get();
        foreach ($applications as $application) {
            if (!$application->campaign || !$this->volunteerFinished($application)) continue;
            $key = 'volunteer:'.$application->volunteer_campaign_id;
            if ($activities->has($key)) continue;
            $review = $reviews->get($key);
            $activities->put($key, [
                'activity_type' => 'volunteer',
                'activity_id' => (int) $application->volunteer_campaign_id,
                'reference_id' => $application->id,
                'title' => $application->campaign->judul,
                'subtitle' => 'Kegiatan relawan selesai',
                'date' => $application->campaign->tanggal_selesai ?? '',
                'has_reviewed' => $review !== null,
                'existing_review' => $review ? $this->serialize($review, $userId) : null,
            ]);
        }
        $unreviewed = $activities->where('has_reviewed', false)->count();

        return response()->json([
            'can_review' => $unreviewed > 0,
            'eligible_count' => $activities->count(),
            'unreviewed_count' => $unreviewed,
            'data' => $activities->values(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'activity_type' => 'required|in:donation,volunteer',
            'activity_id' => 'required|integer|min:1',
            'reference_id' => 'required|integer|min:1',
            ...$this->reviewRules(),
        ]);
        $this->validateComment($data);
        $userId = $request->user()->id;
        if ($data['activity_type'] === 'donation') {
            $reference = DonationTransaction::with('campaign')->where('user_id', $userId)
                ->where('campaign_id', $data['activity_id'])->where('status', 'VERIFIED')
                ->find($data['reference_id']);
        } else {
            $reference = VolunteerApplication::with('campaign')->where('user_id', $userId)
                ->where('volunteer_campaign_id', $data['activity_id'])->where('status', 'approved')
                ->find($data['reference_id']);
            if ($reference && !$this->volunteerFinished($reference)) $reference = null;
        }
        if (!$reference || !$reference->campaign) {
            throw ValidationException::withMessages(['reference_id' => ['Aktivitas belum memenuhi syarat atau bukan milik Anda.']]);
        }
        $data['user_id'] = $userId;
        $data['campaign_title'] = $data['activity_type'] === 'donation'
            ? $reference->campaign->title : $reference->campaign->judul;
        try {
            $review = Review::create($data);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json(['message' => 'Anda sudah mengulas aktivitas ini. Silakan ubah ulasan yang ada.'], 409);
        }

        return response()->json(['message' => 'Ulasan berhasil disimpan.', 'data' => $this->serialize($review->load('user'), $userId)], 201);
    }

    public function update(Request $request, Review $review): JsonResponse
    {
        abort_unless($review->user_id === $request->user()->id, 403, 'Anda hanya dapat mengubah ulasan sendiri.');
        $data = $request->validate($this->reviewRules());
        $this->validateComment($data);
        $review->update($data);

        return response()->json(['message' => 'Ulasan berhasil diperbarui.', 'data' => $this->serialize($review->load('user'), $request->user()->id)]);
    }

    public function destroy(Request $request, Review $review): JsonResponse
    {
        abort_unless($review->user_id === $request->user()->id, 403, 'Anda hanya dapat menghapus ulasan sendiri.');
        $review->delete();

        return response()->json(['message' => 'Ulasan berhasil dihapus.']);
    }

    private function reviewRules(): array
    {
        return ['rating' => 'required|integer|between:1,5', 'comment' => 'required|string|min:5|max:2000', 'is_anonymous' => 'sometimes|boolean'];
    }

    private function validateComment(array &$data): void
    {
        $data['comment'] = trim($data['comment']);
        if (mb_strlen($data['comment']) < 5) {
            throw ValidationException::withMessages(['comment' => ['Ulasan minimal 5 karakter.']]);
        }
    }

    private function volunteerFinished(VolunteerApplication $application): bool
    {
        $campaign = $application->campaign;
        return $campaign && ($campaign->status === 'Selesai' ||
            ($campaign->tanggal_selesai && substr((string) $campaign->tanggal_selesai, 0, 10) < now()->toDateString()));
    }

    private function serialize(Review $review, ?int $viewerId = null): array
    {
        $anonymous = $review->is_anonymous;
        return [
            'id' => $review->id,
            'user_id' => $anonymous && $viewerId !== $review->user_id ? 0 : $review->user_id,
            'user_name' => $anonymous ? 'Sahabat DonGiv' : ($review->user?->name ?? 'Sahabat DonGiv'),
            'user_avatar' => !$anonymous && $review->user?->photo ? asset('storage/'.$review->user->photo) : null,
            'activity_type' => $review->activity_type,
            'activity_id' => $review->activity_id,
            'campaign_title' => $review->campaign_title,
            'rating' => $review->rating,
            'comment' => $review->comment,
            'is_verified' => true,
            'is_anonymous' => $anonymous,
            'created_at_formatted' => $review->created_at->format('d M Y'),
            'time_ago' => $review->created_at->locale('id')->diffForHumans(),
        ];
    }
}
