import asyncio
import uuid
import re
import websockets
from datetime import datetime

# Microsoft Edge Read Aloud WebSocket endpoint
TRUSTED_CLIENT_TOKEN = "6A5AA1D4EAFF4E9FB37E23D68491D6F4"
WSS_URL = f"wss://speech.platform.bing.com/consumer/speech/synthesize/readaloud/edge/v1?TrustedClientToken={TRUSTED_CLIENT_TOKEN}"

HEADERS = {
    "Origin": "chrome-extension://jdiccldimpdaibmpdkjnbmckianbfold",
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0"
}

def date_to_string():
    return datetime.utcnow().strftime("%a %b %d %Y %H:%M:%S GMT+0000 (Coordinated Universal Time)")

async def synthesize_speech(text, output_file, voice="id-ID-ArdiNeural", rate="+0%", pitch="+0Hz"):
    async with websockets.connect(WSS_URL, additional_headers=HEADERS) as ws:
        req_id = uuid.uuid4().hex
        
        # 1. Send speech.config
        config_msg = (
            f"X-Timestamp:{date_to_string()}\r\n"
            "Content-Type:application/json; charset=utf-8\r\n"
            "Path:speech.config\r\n\r\n"
            '{"context":{"synthesis":{"audio":{"metadataoptions":{"sentenceBoundaryEnabled":"false","wordBoundaryEnabled":"false"},'
            '"outputFormat":"audio-24khz-48kbitrate-mono-mp3"}}}}\r\n'
        )
        await ws.send(config_msg)
        
        # 2. Send SSML request
        ssml = (
            f"<speak version='1.0' xmlns='http://www.w3.org/2001/10/synthesis' xml:lang='id-ID'>"
            f"<voice name='{voice}'>"
            f"<prosody rate='{rate}' pitch='{pitch}'>{text}</prosody>"
            f"</voice></speak>"
        )
        
        request_msg = (
            f"X-RequestId:{req_id}\r\n"
            "Content-Type:application/ssml+xml\r\n"
            f"X-Timestamp:{date_to_string()}Z\r\n"
            "Path:ssml\r\n\r\n"
            f"{ssml}"
        )
        await ws.send(request_msg)
        
        # 3. Receive audio chunks
        audio_data = bytearray()
        while True:
            msg = await ws.recv()
            if isinstance(msg, str):
                if "Path:turn.end" in msg:
                    break
            elif isinstance(msg, bytes):
                # Binary audio data contains 2-byte header with header length
                header_len = int.from_bytes(msg[:2], "big")
                header_text = msg[2:2+header_len].decode("utf-8", errors="ignore")
                if "Path:audio" in header_text:
                    audio_payload = msg[2+header_len:]
                    audio_data.extend(audio_payload)
                    
        with open(output_file, "wb") as f:
            f.write(audio_data)
        print(f"Successfully generated {output_file} ({len(audio_data)} bytes)")

if __name__ == "__main__":
    test_text = "Halo, selamat datang di Sistem Informasi Potensi Investasi Terpadu, SIPOTENSI SANGIHE."
    asyncio.run(synthesize_speech(test_text, "test_audio.mp3"))
