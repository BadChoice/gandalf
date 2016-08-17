# Gandalf

A simple library to protect your `Laravel` application by fingerprinting each user Login  

Simply do

```
  Gandalf::trackUser($allowNewUsers)->sendEmailIfNew('emails.newDevice','anEmail@example.com');
```
