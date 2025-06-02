<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // ["PersonnelNo" => "12345", "name" => "Admin", "password" => "12345"],
            // ["PersonnelNo" => "123456", "name" => "Test Karyawan", "password" => "123456"],
            // ["PersonnelNo" => "20122005", "name" => "Iskandar, Se", "password" => "20122005"],
            // ["PersonnelNo" => "20124406", "name" => "Henry Wijaya", "password" => "20124406"],
            // ["PersonnelNo" => "20125493", "name" => "Hendro Sutarto", "password" => "20125493"],
            // ["PersonnelNo" => "20225472", "name" => "Agus Dwi Prasetyo", "password" => "20225472"],
            // ["PersonnelNo" => "20225538", "name" => "Annanta Setiawan Hari Prakoso", "password" => "20225538"],
            // ["PersonnelNo" => "20225539", "name" => "Ariffandi Sastya Indrajaya", "password" => "20225539"],
            // ["PersonnelNo" => "20225558", "name" => "Galationo Vianus Santo", "password" => "20225558"],
            // ["PersonnelNo" => "20225562", "name" => "Renandha Luthfy Agustia", "password" => "20225562"],
            // ["PersonnelNo" => "20225760", "name" => "Li Yuguo", "password" => "20225760"],
            // ["PersonnelNo" => "20225761", "name" => "Xia Zhengxiang", "password" => "20225761"],
            // ["PersonnelNo" => "20324414", "name" => "Melinda Yuliani", "password" => "20324414"],
            // ["PersonnelNo" => "20325598", "name" => "Damaris Tanojo", "password" => "20325598"],
            // ["PersonnelNo" => "20325599", "name" => "Moh. Irfan Ubaidillah. A", "password" => "20325599"],
            // ["PersonnelNo" => "20424415", "name" => "Welly Santoso", "password" => "20424415"],
            // ["PersonnelNo" => "20425679", "name" => "Maulana Andani", "password" => "20425679"],
            // ["PersonnelNo" => "20425682", "name" => "Kurniawan Candra Eka P. M. P", "password" => "20425682"],
            // ["PersonnelNo" => "20425707", "name" => "Allen Stevano Purnomo", "password" => "20425707"],
            // ["PersonnelNo" => "20523029", "name" => "Dimas Christian", "password" => "20523029"],
            // ["PersonnelNo" => "20523031", "name" => "Eko Roy Nova Prasetyo", "password" => "20523031"],
            // ["PersonnelNo" => "20623032", "name" => "Hendrawan Hwadianto", "password" => "20623032"],
            // ["PersonnelNo" => "20623033", "name" => "Martha Meisa", "password" => "20623033"],
            // ["PersonnelNo" => "20624420", "name" => "Veronica Dewi", "password" => "20624420"],
            // ["PersonnelNo" => "20716006", "name" => "Diyah Mukti  kartika sari", "password" => "20716006"],
            // ["PersonnelNo" => "20720001", "name" => "Pujud Rudianto", "password" => "20720001"],
            // ["PersonnelNo" => "20720002", "name" => "Gatot Supriyadi", "password" => "20720002"],
            // ["PersonnelNo" => "20720003", "name" => "Al Misbah", "password" => "20720003"],
            // ["PersonnelNo" => "20720004", "name" => "Suliadi", "password" => "20720004"],
            // ["PersonnelNo" => "20720007", "name" => "Gatot Sarono", "password" => "20720007"],
            // ["PersonnelNo" => "20720008", "name" => "Sukarto A. Djakaria", "password" => "20720008"],
            // ["PersonnelNo" => "20720009", "name" => "Muhammad Imron", "password" => "20720009"],
            // ["PersonnelNo" => "20720010", "name" => "Olivia Andriani", "password" => "20720010"],
            // ["PersonnelNo" => "20720011", "name" => "Paino", "password" => "20720011"],
            // ["PersonnelNo" => "20720012", "name" => "Sulkan", "password" => "20720012"],
            // ["PersonnelNo" => "20720013", "name" => "Solikun", "password" => "20720013"],
            // ["PersonnelNo" => "20720014", "name" => "Akhmad Zainul Arifin", "password" => "20720014"],
            // ["PersonnelNo" => "20720016", "name" => "Madiono", "password" => "20720016"],
            // ["PersonnelNo" => "20720017", "name" => "Mashurin", "password" => "20720017"],
            // ["PersonnelNo" => "20720018", "name" => "Mariyono", "password" => "20720018"],
            // ["PersonnelNo" => "20720019", "name" => "Nur Rochmat", "password" => "20720019"],
            // ["PersonnelNo" => "20720020", "name" => "Indra Liquissa", "password" => "20720020"],
            // ["PersonnelNo" => "20720021", "name" => "Moch.  rifai", "password" => "20720021"],
            // ["PersonnelNo" => "20720022", "name" => "Ahmad Yani", "password" => "20720022"],
            // ["PersonnelNo" => "20720023", "name" => "Sukadi", "password" => "20720023"],
            // ["PersonnelNo" => "20720024", "name" => "Sulistyowati", "password" => "20720024"],
            // ["PersonnelNo" => "20720025", "name" => "Tonti Kusuma Martarika", "password" => "20720025"],
            // ["PersonnelNo" => "20720026", "name" => "Mei Maria Susanti", "password" => "20720026"],
            // ["PersonnelNo" => "20720762", "name" => "Sun Zhiheng", "password" => "20720762"],
            // ["PersonnelNo" => "20723030", "name" => "Narendra Wibisono", "password" => "20723030"],
            // ["PersonnelNo" => "20724417", "name" => "Frisky Rahman Qhadafy", "password" => "20724417"],
            // ["PersonnelNo" => "20724418", "name" => "Tara Icasia Tjukipto", "password" => "20724418"],
            // ["PersonnelNo" => "20724421", "name" => "Riki Renggalis", "password" => "20724421"],
            // ["PersonnelNo" => "20822419", "name" => "Arif Rachmad Wijianto Dip", "password" => "20822419"],
            // ["PersonnelNo" => "20824424", "name" => "Sari Setiorini", "password" => "20824424"],
            // ["PersonnelNo" => "20921027", "name" => "Juliana", "password" => "20921027"],
            // ["PersonnelNo" => "20923034", "name" => "Yonathan Tedja Abdi", "password" => "20923034"],
            // ["PersonnelNo" => "20923035", "name" => "Desyana Friska Kumalasari", "password" => "20923035"],
            // ["PersonnelNo" => "20924425", "name" => "Junianasari / hong chieng", "password" => "20924425"],
            // ["PersonnelNo" => "20924438", "name" => "Toni Widodo", "password" => "20924438"],
            // ["PersonnelNo" => "20924439", "name" => "Jessica Priscillia Gunawan", "password" => "20924439"],
            // ["PersonnelNo" => "20924440", "name" => "Moch. Firdaus Fasa Zulfikri", "password" => "20924440"],
            // ["PersonnelNo" => "21023036", "name" => "Heri Munandar", "password" => "21023036"],
            // ["PersonnelNo" => "21023037", "name" => "Sugeng", "password" => "21023037"],
            // ["PersonnelNo" => "21024453", "name" => "Nadine Aysha Lafinethda", "password" => "21024453"],
            // ["PersonnelNo" => "21024454", "name" => "Agus Erwanto", "password" => "21024454"],
            // ["PersonnelNo" => "21024455", "name" => "Yegita Adi Prasetya", "password" => "21024455"],
            // ["PersonnelNo" => "21024456", "name" => "Yayak Juniarta", "password" => "21024456"],
            // ["PersonnelNo" => "21024462", "name" => "Afif Dwi Risdianto", "password" => "21024462"],
            // ["PersonnelNo" => "21122028", "name" => "Andrew Kusuma Kieman", "password" => "21122028"],
            // ["PersonnelNo" => "21123039", "name" => "Intan Permata Sari", "password" => "21123039"],
            // ["PersonnelNo" => "21123040", "name" => "Eka Saras Lukitasari", "password" => "21123040"],
            // ["PersonnelNo" => "21124041", "name" => "Retno Lestari", "password" => "21124041"],
            // ["PersonnelNo" => "21124042", "name" => "Risa Rosandy", "password" => "21124042"],
            // ["PersonnelNo" => "21124055", "name" => "Moh. Andi Rahmawan", "password" => "21124055"],
            // ["PersonnelNo" => "21124112", "name" => "Ony Harianto", "password" => "21124112"],
            // ["PersonnelNo" => "21124125", "name" => "Heru Kurniawan", "password" => "21124125"],
            // ["PersonnelNo" => "21124145", "name" => "Mashuri", "password" => "21124145"],
            // ["PersonnelNo" => "21124147", "name" => "Lia Maratus", "password" => "21124147"],
            // ["PersonnelNo" => "21124156", "name" => "Puri Dahayus", "password" => "21124156"],
            // ["PersonnelNo" => "21124158", "name" => "Uslimin Hudi Slamet", "password" => "21124158"],
            // ["PersonnelNo" => "21124161", "name" => "Soegianto", "password" => "21124161"],
            // ["PersonnelNo" => "21124170", "name" => "Viving Ariyas Sudana", "password" => "21124170"],
            // ["PersonnelNo" => "21124181", "name" => "Wuliyono", "password" => "21124181"],
            // ["PersonnelNo" => "21124182", "name" => "Sutejo", "password" => "21124182"],
            // ["PersonnelNo" => "21124184", "name" => "Lita Mustopa", "password" => "21124184"],
            // ["PersonnelNo" => "21124187", "name" => "Munadi", "password" => "21124187"],
            // ["PersonnelNo" => "21124203", "name" => "Siti Utmaniarsih", "password" => "21124203"],
            // ["PersonnelNo" => "21124230", "name" => "Widodo", "password" => "21124230"],
            // ["PersonnelNo" => "21124264", "name" => "Achmad Solehudin Al Ayyubi", "password" => "21124264"],
            // ["PersonnelNo" => "21124367", "name" => "Deddy Sutomo", "password" => "21124367"],
            // ["PersonnelNo" => "21124457", "name" => "Ade Sello Sapualam", "password" => "21124457"],
            // ["PersonnelNo" => "21124460", "name" => "Mefiliana Lieguna", "password" => "21124460"],
            // ["PersonnelNo" => "21124461", "name" => "Monika Swastika,,se.mm", "password" => "21124461"],
            // ["PersonnelNo" => "21124471", "name" => "Ahmad Shani Ivan Fauzi", "password" => "21124471"],
            // ["PersonnelNo" => "21124482", "name" => "Boriski Sinaga Ricard", "password" => "21124482"],
            // ["PersonnelNo" => "21124483", "name" => "Johan Chenwidy", "password" => "21124483"],
            // ["PersonnelNo" => "21124484", "name" => "Richard", "password" => "21124484"],
            // ["PersonnelNo" => "21124485", "name" => "Sudarno", "password" => "21124485"],
            // ["PersonnelNo" => "21124757", "name" => "Fu Zhengquan", "password" => "21124757"],
            // ["PersonnelNo" => "21124758", "name" => "Yang Zhibin", "password" => "21124758"],
            // ["PersonnelNo" => "21124759", "name" => "Zhe Zhaowen", "password" => "21124759"],
            // ["PersonnelNo" => "21224492", "name" => "Khoirun Nisa Ashar", "password" => "21224492"],
            // ["PersonnelNo" => "924316", "name" => "Violita Ayu Riyanti", "password" => "924316"],
            // ["PersonnelNo" => "924320", "name" => "Marshell", "password" => "924320"],
            ["PersonnelNo" => "924321", "name" => "Anis Martha", "password" => "924321"],

            // Tambahkan data lainnya...
        ];

        foreach ($data as $user) {
            DB::table('tbl_user_hris_absen')->insert([
                'PersonnelNo' => $user['PersonnelNo'],
                'name' => $user['name'],
                'password' => Hash::make($user['password']),
            ]);
        }
    }
}
