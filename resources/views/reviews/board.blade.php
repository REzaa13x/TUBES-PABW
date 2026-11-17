<section id="ulasan" class="review-board py-16 md:py-20 px-6" aria-labelledby="review-heading">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-wrap items-end justify-between gap-5 mb-8">
            <div>
                <span class="text-blue-600 text-xs font-bold uppercase tracking-wider">Cerita #OrangBaik</span>
                <h2 id="review-heading" class="text-3xl md:text-4xl font-extrabold text-slate-800 mt-3">Ulasan & Rating</h2>
                <p class="text-slate-500 mt-3">Pengalaman nyata dari donatur dan relawan DonGiv.</p>
            </div>
            @if(!($fullPage ?? false))
                <a href="{{ route('reviews.index') }}" class="text-blue-600 font-bold">Lihat semua ulasan &rarr;</a>
            @endif
        </div>
        <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 mb-6 flex flex-wrap items-center gap-6 shadow-sm">
            <div><strong data-average class="text-4xl font-extrabold text-slate-800">0.0</strong><span class="text-slate-400"> / 5</span></div>
            <div><p class="text-amber-500 text-lg" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</p><p data-total class="text-sm text-slate-500 mt-1">Memuat ulasan...</p></div>
            <p class="text-sm text-slate-500 max-w-lg">Ulasan berasal dari donasi terverifikasi atau kegiatan relawan yang telah selesai.</p>
        </div>
        <div class="flex flex-wrap gap-2 mb-6" role="group" aria-label="Kategori ulasan">
            @foreach(['all' => 'Semua Ulasan', 'donation' => 'Donasi', 'volunteer' => 'Relawan'] as $key => $label)
                <button type="button" data-filter="{{ $key }}" aria-pressed="{{ $key === 'all' ? 'true' : 'false' }}" class="review-filter px-5 py-2.5 rounded-full border text-sm font-semibold">{{ $label }}</button>
            @endforeach
        </div>
        <p data-status role="status" class="text-slate-500 py-6">Memuat ulasan...</p>
        <div data-reviews class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5"></div>
        <button data-more type="button" hidden class="mt-6 text-blue-600 font-bold px-5 py-3 border rounded-xl">Muat lebih banyak</button>
        <button data-retry type="button" hidden class="mt-4 text-blue-600 font-bold">Coba lagi</button>
        @if($fullPage ?? false)
            <div class="mt-10 p-6 md:p-8 bg-white border border-slate-100 rounded-3xl">
                <h3 class="text-xl font-extrabold text-slate-800">Bagikan pengalaman Anda</h3>
                @auth
                    <p class="text-sm text-slate-500 mt-2">Pilih donasi terverifikasi atau kegiatan relawan Anda yang telah selesai. Satu ulasan untuk setiap kegiatan.</p>
                    <form data-form class="mt-5 grid gap-4 max-w-2xl">
                        <label class="text-sm font-semibold">Aktivitas<select name="activity" required class="block w-full mt-2 p-3 border rounded-xl"><option value="">Memuat aktivitas...</option></select></label>
                        <label class="text-sm font-semibold">Rating<select name="rating" class="block w-full mt-2 p-3 border rounded-xl">@foreach([5,4,3,2,1] as $rating)<option value="{{ $rating }}">{{ $rating }} bintang</option>@endforeach</select></label>
                        <label class="text-sm font-semibold">Ulasan<textarea name="comment" required minlength="5" maxlength="2000" rows="4" placeholder="Ceritakan pengalaman Anda..." class="block w-full mt-2 p-3 border rounded-xl font-normal"></textarea></label>
                        <label class="text-sm text-slate-600 flex items-center gap-2"><input type="checkbox" name="is_anonymous"> Tampilkan sebagai anonim</label>
                        <div class="flex gap-4 items-center"><button type="submit" class="bg-blue-600 text-white rounded-xl px-6 py-3 font-bold">Kirim ulasan</button><button data-cancel type="button" hidden class="text-slate-500">Batal mengubah</button></div>
                        <p data-feedback role="status" class="text-sm text-slate-600"></p>
                    </form>
                @else
                    <p class="text-slate-500 mt-3">Masuk untuk memberikan ulasan atas aktivitas Anda.</p>
                    <a href="{{ route('login') }}" class="inline-block mt-4 bg-blue-600 text-white rounded-xl px-6 py-3 font-bold">Masuk untuk menulis ulasan</a>
                @endauth
            </div>
        @endif
    </div>
</section>
<style>
    .review-board .review-filter { border-color:#e2e8f0; background:white; color:#475569; }
    .review-board .review-filter[aria-pressed="true"] { background:#2563eb; border-color:#2563eb; color:white; }
    .review-board button:disabled { opacity:.5; cursor:wait; }
    .review-board [hidden] { display:none !important; }
    .review-board button:focus-visible,.review-board a:focus-visible { outline:3px solid #93c5fd; outline-offset:3px; }
</style>
<script>
(() => {
    const board = document.getElementById('ulasan');
    const list = board.querySelector('[data-reviews]');
    const status = board.querySelector('[data-status]');
    const more = board.querySelector('[data-more]');
    const retry = board.querySelector('[data-retry]');
    const form = board.querySelector('[data-form]');
    const fullPage = @json($fullPage ?? false);
    const viewerId = @json(auth()->id());
    const base = @json(url('/ulasan'));
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let filter = 'all', page = 1, requestVersion = 0, editing = null, activities = [], busy = false;
    function node(tag, classes, text) {
        const result = document.createElement(tag);
        result.className = classes;
        if (text !== undefined) result.textContent = text;
        return result;
    }
    async function request(path, options = {}) {
        const response = await fetch(base + path, {
            ...options, credentials:'same-origin',
            headers:{Accept:'application/json','Content-Type':'application/json','X-CSRF-TOKEN':csrf},
            signal: AbortSignal.timeout(30000),
        });
        const body = await response.json();
        if (!response.ok) throw new Error(body.errors ? Object.values(body.errors).flat().join(' ') : body.message || 'Gagal memproses permintaan.');
        return body;
    }
    function card(review) {
        const el = node('article', 'bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col');
        const top = node('div', 'flex items-center justify-between gap-3');
        top.append(node('span', 'text-xs font-bold text-blue-600 bg-blue-50 rounded-full px-3 py-1', review.activity_type === 'volunteer' ? 'Relawan' : 'Donasi'));
        top.append(node('span', 'text-xs text-slate-400', review.time_ago));
        el.append(top);
        const stars = node('p', 'text-amber-500 mt-4 tracking-wider', '\u2605'.repeat(review.rating) + '\u2606'.repeat(5-review.rating));
        stars.setAttribute('aria-label', review.rating + ' dari 5 bintang');
        el.append(stars, node('p', 'text-slate-700 mt-3 leading-relaxed whitespace-pre-wrap break-words', review.comment));
        el.append(node('p', 'text-xs text-slate-500 mt-4', review.campaign_title));
        el.append(node('p', 'text-sm font-bold text-slate-800 mt-5', review.user_name));
        el.append(node('p', 'text-xs text-emerald-600 mt-1', 'Aktivitas terverifikasi'));
        if (form && review.user_id === viewerId) {
            const actions = node('div','flex gap-4 mt-4 text-sm');
            const edit = node('button','text-blue-600 font-bold','Ubah'); edit.type='button';
            edit.onclick = () => {
                if (busy) return;
                editing = review.id;
                const option = new Option(review.campaign_title, 'editing');
                form.elements.activity.replaceChildren(option); form.elements.activity.disabled=true;
                form.elements.rating.value=review.rating; form.elements.comment.value=review.comment;
                form.elements.is_anonymous.checked=review.is_anonymous;
                form.querySelector('[type="submit"]').textContent='Simpan perubahan';
                board.querySelector('[data-cancel]').hidden=false;
                form.scrollIntoView({behavior:'smooth', block:'center'});
            };
            const remove = node('button','text-red-600 font-bold','Hapus'); remove.type='button';
            remove.onclick = async () => {
                if (busy || !confirm('Hapus ulasan ini?')) return;
                busy=true; remove.disabled=true;
                try { await request('/'+review.id,{method:'DELETE'}); resetForm(); await load(); await loadActivities(); }
                catch (error) { status.hidden=false; status.textContent=error.message; }
                finally { busy=false; remove.disabled=false; }
            };
            actions.append(edit,remove);el.append(actions);
        }
        return el;
    }
    async function load(append=false) {
        const version=++requestVersion;
        const targetPage=append ? page+1 : 1;
        status.hidden=false; status.textContent='Memuat ulasan...'; more.disabled=true; retry.hidden=true;
        try {
            const body=await request('/data?'+new URLSearchParams({activity_type:filter,page:targetPage,per_page:fullPage?12:3}));
            if (version!==requestVersion) return;
            if (!append) list.replaceChildren();
            body.data.forEach(review=>list.append(card(review)));
            page=targetPage;
            board.querySelector('[data-average]').textContent=Number(body.summary.average_rating).toFixed(1);
            board.querySelector('[data-total]').textContent=body.summary.total_reviews+' ulasan';
            status.textContent='Belum ada ulasan untuk kategori ini.';status.hidden=list.children.length>0;
            more.hidden=!fullPage || page>=body.meta.last_page;
        } catch(error) {
            if (version!==requestVersion) return;
            status.hidden=false;status.textContent='Gagal memuat ulasan. Silakan coba lagi.';retry.hidden=false;more.hidden=true;
        } finally { if(version===requestVersion) more.disabled=false; }
    }
    function fillActivities() {
        const select=form.elements.activity; select.disabled=false;
        select.replaceChildren(new Option('Pilih aktivitas',''));
        activities.filter(a=>!a.has_reviewed).forEach((a,i)=>select.add(new Option((a.activity_type==='volunteer'?'Relawan':'Donasi')+' - '+a.title,String(activities.indexOf(a)))));
        if (select.options.length===1) select.options[0].textContent='Belum ada aktivitas yang dapat diulas';
    }
    function resetForm() {
        if (!form) return;
        editing=null;form.reset();fillActivities();board.querySelector('[data-cancel]').hidden=true;
        form.querySelector('[type="submit"]').textContent='Kirim ulasan';
    }
    async function loadActivities() {
        if (!form) return;
        try { const body=await request('/eligibility');activities=body.data;if (!editing) fillActivities(); }
        catch(error) { form.querySelector('[data-feedback]').textContent='Gagal memuat aktivitas. Muat ulang halaman untuk mencoba lagi.'; }
    }
    board.querySelectorAll('[data-filter]').forEach(button=>button.onclick=()=>{
        filter=button.dataset.filter;
        board.querySelectorAll('[data-filter]').forEach(b=>b.setAttribute('aria-pressed',String(b===button)));
        load();
    });
    more.onclick=()=>load(true);retry.onclick=()=>load();
    if (form) {
        board.querySelector('[data-cancel]').onclick=()=>{if(!busy) resetForm();};
        form.onsubmit=async event=>{
            event.preventDefault(); if(busy || !form.reportValidity()) return;
            const comment=form.elements.comment.value.trim();
            if(comment.length<5){form.querySelector('[data-feedback]').textContent='Ulasan minimal 5 karakter.';return;}
            const payload={rating:Number(form.elements.rating.value),comment,is_anonymous:form.elements.is_anonymous.checked};
            if(!editing){
                const activity=activities[Number(form.elements.activity.value)];
                if(!activity || activity.has_reviewed)return;
                Object.assign(payload,{activity_type:activity.activity_type,activity_id:activity.activity_id,reference_id:activity.reference_id});
            }
            busy=true;const submit=form.querySelector('[type="submit"]');submit.disabled=true;
            try {
                const body=await request(editing?'/'+editing:'',{method:editing?'PUT':'POST',body:JSON.stringify(payload)});
                resetForm();form.querySelector('[data-feedback]').textContent=body.message;
                await load();await loadActivities();
            }catch(error){form.querySelector('[data-feedback]').textContent=error.message;}
            finally{busy=false;submit.disabled=false;}
        };
    }
    load();loadActivities();
})();
</script>

