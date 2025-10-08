import shutil
import threading
import time
import zipfile
from flask import Flask, request, send_file
import requests, csv, io, os

app = Flask(__name__)
s = requests.Session()

def fself_to_elf(path_in, path_out):
    with open(path_in, "rb") as f:
        f.seek(24)
        elf_size = int.from_bytes(f.read(8), "little")
        f.seek(32, 1)
        elf_offset = int.from_bytes(f.read(8), "little")
        f.seek(elf_offset)
        with open(path_out, "wb") as fout:
            fout.write(f.read(elf_size))

db = []

def FetchDB():
    global db
    while True:
        r = s.get("https://raw.githubusercontent.com/Team-CBPS/cbps-db/master/cbpsdb.csv")
        reader = csv.DictReader(r.text.splitlines()[1:], r.text.split("\n")[0].split(","))
        db = []
        for row in reader:
            db.append(row)
        time.sleep(60*10)

threading.Thread(target=FetchDB).start()

@app.route("/")
@app.route("/<int:page>")
def index(page = 0):
    offset = page * 100
    if not request.user_agent.string.startswith("libhttp/"):
        return "Hello, world!"
    
    ret = ""
    for entry in [a for a in db if a["type"] == "VPK"][offset:offset + 100]:
        entry_id = entry["id"]
        name = entry["title"].replace(" ", "_")
        ret += f'<a href="d/{entry_id}-{name}.pkg"></a><br>'

    return ret

@app.route("/d/<path>")
def download(path):
    if not request.user_agent.string.startswith("libhttp/"):
        return "Hello, world!"
    
    entry_id, _ = path.split("-")
    entry = next(filter(lambda x: x["id"] == entry_id, db), None)
    if entry is None:
        return "Not found", 404
    titleid = entry_id.split("_")[0]
    content_id = f"CBPS00-{titleid}_00-XXXXXXXXXXXXXXXX"
    cache_path = f"cache/{content_id}.pkg"
    name = entry["title"].replace(" ", "_")

    if not os.path.exists(cache_path):
        print(f"Downloading {path}...")
        r = s.get(entry["download_url"])
        if r.status_code != 200:
            return "Not found", 404

        contents_root = f"tmp/{entry_id}-{name}/CONTENTS"

        os.makedirs(contents_root, exist_ok=True)
        arc = zipfile.PyZipFile(io.BytesIO(r.content), "r")
        arc.extractall(contents_root)
        arc.close()

        elf_name = f"tmp/{entry_id}-{name}.elf"
        fself_to_elf(f"{contents_root}/eboot.bin", elf_name)
        os.system(f"vita-make-fself -e {elf_name} {contents_root}/eboot.bin") # this only works with my fork
        with open(f"{contents_root}/../package.conf", "w") as f:
            f.write(f"Passcode = {'0' * 32}\n")
            f.close()
        os.system(f"./make_pkg.exe -C {contents_root}/.. -o {os.path.abspath('cache')} --content-id {content_id} --content-type PSP2GD --package-version 1.00 --drm-type Free")
        os.system(f"rm -rf {contents_root}/..")

    return send_file(cache_path)

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8080)
