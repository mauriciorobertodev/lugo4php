PROTO_DIR=src/protos
OUTPUT_DIR=output
# PROTO_FILE=/protos/server.proto
# PROTO_FILE=/protos/broadcast.proto
PROTO_FILE=/protos/physics.proto
# PROTO_FILE=/protos/remote.proto

generate-proto:
	docker run --rm -v $(PWD)/$(PROTO_DIR):/protos -v $(PWD)/$(OUTPUT_DIR):/output znly/protoc \
		-I/protos \
		--php_out=/output \
		--grpc_out=/output \
		--plugin=protoc-gen-grpc=/usr/bin/grpc_php_plugin \
		$(PROTO_FILE)
	
delete-output:
	sudo rm -rf $(OUTPUT_DIR)

dev:
	docker compose -f ./example/bot/docker-compose.yml up --remove-orphans

dev-build:
	docker compose -f ./example/bot/docker-compose.yml up --build --remove-orphans

build-docker-grpc:
	docker build -f ./.docker/Dockerfile.grpc -t php-grpc-base:8.3.11 .

build-docker-grpc-tag:
	docker tag php-grpc-base:8.3.11 mauricioroberto/php-grpc-base:8.3.11

push-docker-grpc:
	docker push mauricioroberto/php-grpc-base:8.3.11
