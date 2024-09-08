PROTO_DIR=/protos
OUTPUT_DIR=output
PROTO_FILES=$(PROTO_DIR)/server.proto $(PROTO_DIR)/broadcast.proto $(PROTO_DIR)/physics.proto $(PROTO_DIR)/remote.proto

generate:
	docker run --rm -v $(PWD)/$(PROTO_DIR):/protos -v $(PWD)/$(OUTPUT_DIR):/output \
		znly/protoc \
		-I/protos \
		--php_out=/output \
		--grpc_out=/output \
		--plugin=protoc-gen-grpc=/usr/bin/grpc_php_plugin \
		$(PROTO_FILES) && sudo chmod -R 777 $(PWD)/$(OUTPUT_DIR)

update-output:
	sudo chmod 777 -R output
	
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
