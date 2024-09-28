PROTO_DIR=/protos
OUTPUT_DIR=output
PROTO_FILES=$(PROTO_DIR)/server.proto $(PROTO_DIR)/broadcast.proto $(PROTO_DIR)/physics.proto $(PROTO_DIR)/remote.proto

output-generate:
	docker run --rm -v $(PWD)/$(PROTO_DIR):/protos -v $(PWD)/$(OUTPUT_DIR):/output \
		znly/protoc \
		-I/protos \
		--php_out=/output \
		--grpc_out=/output \
		--plugin=protoc-gen-grpc=/usr/bin/grpc_php_plugin \
		$(PROTO_FILES) && sudo chmod -R 777 $(PWD)/$(OUTPUT_DIR)

output-update:
	sudo chmod 777 -R output
	
output-delete:
	sudo rm -rf $(OUTPUT_DIR)

docker-build:
	docker build -f ./.docker/Dockerfile.release -t mauricioroberto/lugo4php:8.3.11 .

docker-push:
	docker push mauricioroberto/lugo4php:8.3.11

docker-build-dev:
	docker build -f ./.docker/Dockerfile.dev -t mauricioroberto/lugo4php:8.3.11-dev .

docker-push-dev:
	docker push mauricioroberto/lugo4php:8.3.11-dev
