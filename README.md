Docker deployment  

Host environment: AWS EC2 Ubuntu 22.04  
Deployed url in https: https://slotify.luciuswong.com  
Location of docker-compose: /srv/www/slotify 

---  

Useful Certbot commands  

Dry run for certificates

```docker compose run --rm  certbot certonly --webroot --webroot-path /var/www/certbot/ --dry-run -d slotify.luciuswong.com```

Renew certificates

```$ docker compose run --rm certbot renew```

--- 

Referred documentation

https://mindsers.blog/post/https-using-nginx-certbot-docker/