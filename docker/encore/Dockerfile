FROM node:12

ARG docker_proxy
ARG dockers_proxy
ARG no_proxy

env HTTP_PROXY=${docker_proxy}
env http_proxy=${docker_proxy}
env HTTPS_PROXY=${dockers_proxy}
env https_proxy=${dockers_proxy}
env NO_PROXY=${no_proxy}
env no_proxy=${no_proxy}

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
		apt-transport-https

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

RUN npm install --force

RUN npm rebuild node-sass

CMD ["npm", "run", "dev-server"]
