FROM debian:stretch-20220527

RUN apt-get update && apt-get install -y sendmail openssl

ADD entrypoint.sh /entrypoint.sh

RUN chmod u+x /entrypoint.sh

EXPOSE 25

ENTRYPOINT ["bash", "/entrypoint.sh"]
