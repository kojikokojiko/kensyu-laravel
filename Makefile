
.PHONY: tree
tree:
	tree -I 'node_modules|vendor|.git' > directory_structure.txt

.PHONY: up
up:
	docker-compose up -d

.PHONY: up-build
up-build:
	docker-compose up -d --build

.PHONY: down
down:
	docker-compose down







php artisan make:model Article -m
php artisan migrate
php artisan make:controller ArticleController




#参考
#https://chatgpt.com/c/dccc61d6-7bdd-45ab-9afd-acc634fbd56f
