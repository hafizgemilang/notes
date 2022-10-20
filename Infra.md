# Service Enumeration
```python
nmap -sC -sV -Pn -n -p- --min-rate=400 --min-parallelism=512 [ip]
```

# Recon
```python
dirb http://[ip]

mysql -h [ip] -u [user] -p

sudo john --format=mysql-sha1 -w=/usr/share/wordlists/rockyou.txt list

wpscan --url [ip]

searchsploit [app-version]

hydra -v -V -u -L "user" -P "pass" -t 10 -u [ip] ssh

ip -a


```

# Privilege Escalation
```python

sudo -l
sudo "command-root"

whoami
cat /etc/shadow
```