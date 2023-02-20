#
# Makefile
#
# for development environment make dev
# for production environment make prd
#

dev:
	docker-compose -f docker/docker-compose-dev.yml -p dashwall-dev down && \
	docker-compose -f docker/docker-compose-dev.yml -p dashwall-dev rm -f && \
	docker-compose -f docker/docker-compose-dev.yml -p dashwall-dev build --no-cache && \
	docker-compose -f docker/docker-compose-dev.yml -p dashwall-dev up -d --remove-orphans && \
	docker image prune -f --filter="dangling=true"

#tst:
#	docker-compose -f docker/docker-compose-tst.yml -p dashwall down && \
#	docker-compose -f docker/docker-compose-tst.yml -p dashwall rm -f && \
#	docker-compose -f docker/docker-compose-tst.yml -p dashwall build --no-cache && \
#	docker-compose -f docker/docker-compose-tst.yml -p dashwall up -d --remove-orphans && \
#	docker image prune -f --filter="dangling=true"
#
#prd:
#	docker-compose -f docker/docker-compose-prd.yml -p dashwall down && \
#	docker-compose -f docker/docker-compose-prd.yml -p dashwall rm -f && \
#	docker-compose -f docker/docker-compose-prd.yml -p dashwall build --no-cache && \
#	docker-compose -f docker/docker-compose-prd.yml -p dashwall up -d --remove-orphans && \
#	docker image prune -f --filter="dangling=true"
