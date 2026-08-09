import requests
import sys
import os
from dotenv import load_dotenv

load_dotenv()

GITHUB_TOKEN = os.environ["GITHUB_PUSH_TOKEN"]

REPO_OWNER = "Team-CBPS"
REPO_NAME = "cbps-db"
MERGE_WITH_MASTER = sys.argv[1]
MASTER_BRANCH = "main"

header = {"Authorization":"Bearer "+GITHUB_TOKEN,
		"Accept":"application/vnd.github+json",
		"X-GitHub-Api-Version":"2022-11-28"}

postdata = {"title":sys.argv[2],
			"body":sys.argv[3],
			"head":MERGE_WITH_MASTER,
			"base":MASTER_BRANCH}

pullRequestRequest = requests.post("https://api.github.com/repos/"+REPO_OWNER+"/"+REPO_NAME+"/pulls",headers=header,json=postdata)

print(pullRequestRequest.json()["html_url"])
