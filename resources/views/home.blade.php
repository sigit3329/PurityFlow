@extends('layout.app')
@section('container')

<div class="container mt-5">
    <div class="jumbotron">
        <h1 class="display-5">Selamat Datang di Website PurityFlow</h1>
        <p class="lead">Sistem pemantauan kualitas air dengan data TDS terkini.</p>
        <hr class="my-4">
        <div class="text-left">
            <p>Gunakan sistem ini untuk mendapatkan informasi mendetail tentang kualitas air dan total penggunaan galon.</p>
            <p>PurityFlow adalah solusi terbaik untuk memantau kualitas air Anda. Dengan sistem ini, Anda dapat melihat data Total Dissolved Solids (TDS) secara real-time.</p>
            <p>TDS adalah ukuran yang menunjukkan jumlah zat padat terlarut dalam air. Informasi ini penting untuk memastikan bahwa air yang Anda gunakan berada dalam kondisi yang baik dan aman untuk berbagai keperluan, termasuk konsumsi, pertanian, dan industri.</p>
            <p>Fitur-fitur utama dari PurityFlow meliputi:</p>
            <ul>
                <li>Pemantauan Real-Time: Dapatkan data TDS terkini kapan saja dan di mana saja.</li>
                <li>Laporan Harian, Mingguan, dan Bulanan: Analisis data dengan grafik yang disediakan untuk melihat tren dan pola kualitas air Anda.</li>
                <li>Kualitas Air Terperinci: Lihat informasi mendetail tentang kualitas air berdasarkan nilai TDS, termasuk saran untuk perbaikan jika diperlukan.</li>
                <li>Penggunaan Galon Terpantau: Ketahui jumlah galon air yang telah digunakan, membantu Anda dalam pengelolaan dan perencanaan penggunaan air.</li>
            </ul>
            <p>Dengan menggunakan PurityFlow, Anda tidak hanya memantau kualitas air, tetapi juga dapat membuat keputusan yang lebih baik terkait penggunaan air, meningkatkan efisiensi, dan memastikan bahwa air yang Anda gunakan memenuhi standar kualitas yang diperlukan.</p>
            <p>Sistem ini dirancang untuk kemudahan penggunaan, sehingga Anda dapat dengan mudah memahami data dan informasi yang disajikan.</p>
            <p>Kami berkomitmen untuk menyediakan alat terbaik untuk membantu Anda dalam menjaga dan mengelola sumber daya air Anda.</p>
        </div>
        <a class="btn btn-primary btn-lg mt-4" href="{{ route('tds') }}" role="button">Lihat Dashboard</a>
    </div>
</div>

@endsection
