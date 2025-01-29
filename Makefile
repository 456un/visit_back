# Makefile

help: ## Показать эту справку
	echo "Доступные команды:"
	grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  make %-10s - %s\n", $$1, $$2}'

git-push: ## Запушить в git
	git add --all
	git commit -m "$(mes)"
	git push origin $(shell git rev-parse --abbrev-ref HEAD)

run: ## Запуск контейнера
	docker compose up -d

stop: ## Остановка контейнера
	docker compose stop

build: ## Сборка контейнера
	docker compose up -d --build