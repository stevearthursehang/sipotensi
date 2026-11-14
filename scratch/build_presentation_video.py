import os
import sys
import json
import subprocess
from gtts import gTTS
import imageio_ffmpeg

ffmpeg_exe = imageio_ffmpeg.get_ffmpeg_exe()

SCENES = [
    {
        "id": "scene_01",
        "title": "Pembuka & Judul Resmi",
        "image": "c:/xampp/htdocs/gis/scratch/slide_title.png",
        "text": "Selamat datang di video presentasi resmi inovasi daerah SIPOTENSI SANGIHE, Sistem Informasi Potensi Investasi Terpadu berbasis WebGIS dan Tiga Dimensi Digital Twin, persembahan Pemerintah Kabupaten Kepulauan Sangihe."
    },
    {
        "id": "scene_02",
        "title": "Latar Belakang & Geostrategis Kawasan",
        "image": "c:/xampp/htdocs/gis/public/images/proposal/gambar_a1.png",
        "text": "Mengapa inovasi ini dibangun? Kabupaten Kepulauan Sangihe terletak strategis di beranda terdepan perbatasan Pasifik dan Alur Laut Kepulauan Indonesia ALKI Dua. Selama ini, promosi investasi terkendala jarak pulau terluar, biaya survei fisik yang mahal, serta ketiadaan peta digital terpadu."
    },
    {
        "id": "scene_03",
        "title": "Antarmuka Utama Peta Spasial WebGIS",
        "image": "c:/xampp/htdocs/gis/public/images/proposal/gambar_l1.png",
        "text": "Sebagai solusi terobosan, SIPOTENSI SANGIHE menyajikan peta geospasial interaktif 29 titik peluang investasi siap tawar senilai total 131 miliar rupiah, lengkap dengan batas 167 desa dan kelurahan, serta filter ketersediaan jalan, listrik PLN, air bersih, dan jaringan internet empat G."
    },
    {
        "id": "scene_04",
        "title": "Model 3D Digital Twin WebGL",
        "image": "c:/xampp/htdocs/gis/public/images/proposal/gambar_l2.png",
        "text": "Inovasi ini mengusung teknologi modern Model Tiga Dimensi Digital Twin berbasis WebGL Three js. Investor dapat menjelajahi kontur topografi pulau, memutar orientasi kamera tiga ratus enam puluh derajat, dan melihat pilar markah proyek secara dinamis tanpa aplikasi tambahan."
    },
    {
        "id": "scene_05",
        "title": "Kalkulator Kelayakan Finansial & Simulasi ROI",
        "image": "c:/xampp/htdocs/gis/public/images/proposal/gambar_l3.png",
        "text": "Setiap peluang dilengkapi Kalkulator Simulasi Finansial Interaktif. Calon investor dapat memodelkan anggaran modal, menguji persentase imbal hasil atau return on investment, melihat estimasi waktu balik modal, serta memantau grafik proyeksi arus kas kumulatif secara transparan."
    },
    {
        "id": "scene_06",
        "title": "Matriks Komparasi Peluang Head-to-Head",
        "image": "c:/xampp/htdocs/gis/public/images/proposal/gambar_l4.png",
        "text": "Untuk mempermudah pengambilan keputusan bisnis, fitur Matriks Komparasi Head to Head memungkinkan pemodal membandingkan hingga tiga proyek sekaligus, mencakup parameter legalitas lahan, kesiapan utilitas dasar, jarak logistik ke Pelabuhan Tahuna, serta analisis SWOT komprehensif."
    },
    {
        "id": "scene_07",
        "title": "Dashboard Statistik & Infografis Terbuka",
        "image": "c:/xampp/htdocs/gis/public/images/proposal/gambar_l5.png",
        "text": "Transparansi data diwujudkan melalui Dashboard Statistik Terbuka yang menampilkan empat indikator kinerja utama, diagram komposisi modal per sektor, sebaran antarkecamatan, serta kemudahan mengunduh data terbuka untuk perencana daerah dan akademisi."
    },
    {
        "id": "scene_08",
        "title": "Dokumen Prospektus Investasi PDF Resmi A4",
        "image": "c:/xampp/htdocs/gis/public/images/proposal/gambar_l6.png",
        "text": "Sistem menyediakan Generator Dokumen Prospektus Investasi berstandar resmi ukuran A empat lengkap dengan kop pemerintah daerah, koordinat presisi, profil bisnis, dan kode verifikasi yang dapat diunduh instan untuk bahan rapat direksi pemodal."
    },
    {
        "id": "scene_09",
        "title": "Portal Akun Investor & Formulir Digital e-LoI",
        "image": "c:/xampp/htdocs/gis/public/images/proposal/gambar_l7.png",
        "text": "Calon investor dapat mendaftar mandiri dan menyampaikan formulir Pernyataan Minat atau Letter of Intent digital terstandarisasi BKPM Republik Indonesia, yang langsung diverifikasi secara terpadu oleh tim DPMPTSP Kabupaten Kepulauan Sangihe."
    },
    {
        "id": "scene_10",
        "title": "Penutup & Ajakan Berinvestasi",
        "image": "c:/xampp/htdocs/gis/scratch/slide_closing.png",
        "text": "Dengan inovasi SIPOTENSI SANGIHE, Kabupaten Kepulauan Sangihe siap menjadi destinasi penanaman modal yang berdaya saing global, terpercaya, dan berkelanjutan. Mari bersama membangun ekonomi perbatasan Indonesia. Terima kasih."
    }
]

def get_audio_duration(file_path):
    cmd = [
        ffmpeg_exe, "-i", file_path,
        "-f", "null", "-"
    ]
    res = subprocess.run(cmd, stderr=subprocess.PIPE, stdout=subprocess.PIPE, text=True)
    import re
    match = re.search(r"Duration:\s*(\d+):(\d+):(\d+\.\d+)", res.stderr)
    if match:
        h, m, s = match.groups()
        return int(h) * 3600 + int(m) * 60 + float(s)
    return 10.0

def process():
    scratch_dir = "c:/xampp/htdocs/gis/scratch"
    os.makedirs(scratch_dir, exist_ok=True)
    
    clip_files = []
    
    print("=== STEP 1: GENERATE MALE VOICEOVER & VIDEO CLIPS ===")
    for idx, sc in enumerate(SCENES):
        scene_id = sc["id"]
        raw_mp3 = os.path.join(scratch_dir, f"{scene_id}_raw.mp3")
        male_mp3 = os.path.join(scratch_dir, f"{scene_id}_male.mp3")
        clip_mp4 = os.path.join(scratch_dir, f"{scene_id}.mp4")
        
        print(f"[{idx+1}/{len(SCENES)}] Processing {sc['title']}...")
        
        # 1. Generate Voiceover via gTTS
        tts = gTTS(text=sc["text"], lang="id", slow=False)
        tts.save(raw_mp3)
        
        # 2. Formant/Pitch shift to authoritative Indonesian male voice
        subprocess.run([
            ffmpeg_exe, "-y", "-i", raw_mp3,
            "-af", "asetrate=24000*0.84,aresample=24000,atempo=1.19,equalizer=f=120:width_type=o:width=1:g=3.5",
            "-c:a", "libmp3lame", "-b:a", "192k", male_mp3
        ], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)
        
        duration = get_audio_duration(male_mp3) + 0.6
        print(f"    Audio duration: {duration:.2f} seconds")
        
        # 3. Create high-def 1080p video clip from image + audio with subtle zoom
        # Pad image to 1920x1080 with dark navy letterbox if needed
        vf_filter = (
            "scale=1920:1080:force_original_aspect_ratio=decrease,"
            "pad=1920:1080:(ow-iw)/2:(oh-ih)/2:color=0x0a0f1d,"
            "format=yuv420p"
        )
        
        subprocess.run([
            ffmpeg_exe, "-y",
            "-loop", "1", "-i", sc["image"],
            "-i", male_mp3,
            "-c:v", "libx264", "-tune", "stillimage", "-preset", "fast",
            "-vf", vf_filter,
            "-c:a", "aac", "-b:a", "192k",
            "-t", str(duration),
            "-pix_fmt", "yuv420p",
            clip_mp4
        ], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)
        
        clip_files.append(clip_mp4)
        print(f"    Clip generated: {clip_mp4} ({os.path.getsize(clip_mp4)} bytes)")

    # STEP 2: CONCATENATE ALL CLIPS
    print("\n=== STEP 2: CONCATENATE ALL SCENES INTO FINAL VIDEO ===")
    concat_list_file = os.path.join(scratch_dir, "concat_list.txt")
    with open(concat_list_file, "w") as f:
        for c in clip_files:
            # Format for ffmpeg concat demuxer (use forward slashes or escape)
            clean_path = c.replace("\\", "/")
            f.write(f"file '{clean_path}'\n")
            
    final_public_path = "c:/xampp/htdocs/gis/public/videos/SIPOTENSI_SANGIHE_PRESENTASI_RESMI.mp4"
    final_root_path = "c:/xampp/htdocs/gis/SIPOTENSI_SANGIHE_PRESENTASI_RESMI.mp4"
    final_art_path = "C:/Users/steve/.gemini/antigravity/brain/64b76a5e-9dea-4de1-be08-4a697f117eb4/SIPOTENSI_SANGIHE_PRESENTASI_RESMI.mp4"
    
    subprocess.run([
        ffmpeg_exe, "-y",
        "-f", "concat", "-safe", "0",
        "-i", concat_list_file,
        "-c", "copy",
        final_public_path
    ], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, check=True)
    
    import shutil
    shutil.copyfile(final_public_path, final_root_path)
    shutil.copyfile(final_public_path, final_art_path)
    
    print("\n=== SUCCESS! VIDEO GENERATION COMPLETE ===")
    print(f"Public URL: http://127.0.0.1:8000/videos/SIPOTENSI_SANGIHE_PRESENTASI_RESMI.mp4")
    print(f"Local Path: {final_public_path} ({os.path.getsize(final_public_path)} bytes)")
    print(f"Root Path: {final_root_path}")
    print(f"Artifact Path: {final_art_path}")

if __name__ == "__main__":
    process()
