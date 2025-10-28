FROM ubuntu:24.04

# 必要なパッケージをインストール（例としてvim, curl, gitを追加）
RUN apt-get update && apt-get install -y \
    vim \
    curl \
    git \
    && rm -rf /var/lib/apt/lists/*

CMD ["/bin/bash"] 