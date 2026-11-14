import os
from gtts import gTTS
import imageio_ffmpeg

ffmpeg_exe = imageio_ffmpeg.get_ffmpeg_exe()

text = "Halo, selamat datang di video presentasi resmi SIPOTENSI SANGIHE, Sistem Informasi Potensi Investasi Terpadu Kabupaten Kepulauan Sangihe."

# 1. Generate base audio with gTTS (Indonesian)
tts = gTTS(text=text, lang='id', slow=False)
tts.save("temp_raw.mp3")

import subprocess

# 2. Transform to deep, authoritative male pitch using ffmpeg
subprocess.run([
    ffmpeg_exe, "-y", "-i", "temp_raw.mp3",
    "-af", "asetrate=24000*0.84,aresample=24000,atempo=1.19,equalizer=f=120:width_type=o:width=1:g=3.5",
    "-c:a", "libmp3lame", "-b:a", "192k", "test_male.mp3"
], check=True)

print("Generated test_male.mp3 size:", os.path.getsize("test_male.mp3"))
