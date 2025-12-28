// Contoh kode Flutter untuk mengakses endpoint API campaign
import 'dart:convert';
import 'package:http/http.dart' as http;

class Campaign {
  final int id;
  final String title;
  final String description;
  final String image;
  final String targetAmount;
  final String currentAmount;
  final String endDate;
  final String status;
  final String createdAt;

  Campaign({
    required this.id,
    required this.title,
    required this.description,
    required this.image,
    required this.targetAmount,
    required this.currentAmount,
    required this.endDate,
    required this.status,
    required this.createdAt,
  });

  factory Campaign.fromJson(Map<String, dynamic> json) {
    return Campaign(
      id: json['id'],
      title: json['title'],
      description: json['description'],
      image: json['image'],
      targetAmount: json['target_amount'],
      currentAmount: json['current_amount'],
      endDate: json['end_date'],
      status: json['status'],
      createdAt: json['created_at'],
    );
  }
}

class CampaignService {
  // Ganti dengan URL server Laravel kamu
  static const String baseUrl = 'http://127.0.0.1:8000/api/campaigns';

  static Future<List<Campaign>> fetchCampaigns() async {
    try {
      final response = await http.get(
        Uri.parse(baseUrl),
        headers: {
          'Content-Type': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final List<dynamic> campaignList = data['data'];
        
        return campaignList
            .map((json) => Campaign.fromJson(json))
            .toList();
      } else {
        throw Exception('Gagal memuat data campaigns: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Gagal mengambil data dari server: $e');
    }
  }
}

// Contoh penggunaan di dalam widget Flutter
/*
  @override
  void initState() {
    super.initState();
    _fetchCampaigns();
  }

  Future<void> _fetchCampaigns() async {
    try {
      final campaigns = await CampaignService.fetchCampaigns();
      setState(() {
        _campaigns = campaigns;
      });
    } catch (e) {
      print('Error: $e');
      // Tampilkan pesan error ke pengguna
    }
  }
*/