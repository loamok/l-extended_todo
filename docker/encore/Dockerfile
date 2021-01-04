FROM node:12

ARG docker_proxy
ARG dockers_proxy
ARG no_proxy
ARG apply_acl_to_root

env HTTP_PROXY=${docker_proxy}
env http_proxy=${docker_proxy}
env HTTPS_PROXY=${dockers_proxy}
env https_proxy=${dockers_proxy}
env NO_PROXY=${no_proxy}
env no_proxy=${no_proxy}
env APPLY_ACL_TO_ROOT=${apply_acl_to_root}

run if [ ! -z ${docker_proxy} ] ; then echo "Proxy" ; else echo "no Proxy"; fi

# configuring proxy
RUN if [ ! -z ${docker_proxy} ] ; then \
        printf 'Acquire::http::Proxy "%s";' $docker_proxy >> /etc/apt/apt.conf; \
        echo "" >> /etc/apt/apt.conf; \
    fi;
RUN if [ ! -z ${docker_proxy} ] ; then \
        printf 'Acquire::https::Proxy "%s";' $dockers_proxy >> /etc/apt/apt.conf; \
        echo "" >> /etc/apt/apt.conf; \
    fi;

RUN apt-get update && \
	apt-get install -y \
		curl \
		apt-transport-https \
                vim \
                less \
                acl 

RUN if [ ! -z ${docker_proxy} ] ; then \
        curl -x "${dockers_proxy}" -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
	echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list; \
    else \
        curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
	echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list; \
    fi;

RUN apt-get update && apt-get install -y yarn

RUN if [ ! -z ${docker_proxy} ] ; then npm config set proxy ${docker_proxy} ; fi
RUN if [ ! -z ${dockers_proxy} ] ; then npm config set https-proxy ${dockers_proxy} ; fi

WORKDIR /var/www/l-extended_todo

COPY launch_me_at_boot.sh /var/www/launch_me_at_boot.sh
COPY make-acls.sh /var/www/make-acls.sh
RUN test -f /var/www/make-acls.sh && chmod +x /var/www/make-acls.sh;
RUN test -f /var/www/launch_me_at_boot.sh && chmod +x /var/www/launch_me_at_boot.sh;

ENTRYPOINT ["/var/www/launch_me_at_boot.sh"]
