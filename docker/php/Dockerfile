# ─────────────────────────────────────────────────────────────
#  Tip-Center · PHP 8.2-FPM + Supervisor
# ─────────────────────────────────────────────────────────────
FROM php:8.2-fpm

ARG user=www-app
ARG uid=1000

# ── System dependencies ────────────────────────────────────────────────
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    zip \
    default-mysql-client \
    libpng-dev \
    libgd-dev \
    libmagickwand-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ── PHP extensions ─────────────────────────────────────────────────────
RUN pecl install imagick \
    && docker-php-ext-enable imagick

RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j"$(nproc)" \
       pdo_mysql \
       mbstring \
       exif \
       pcntl \
       bcmath \
       gd \
       zip \
       opcache \
       intl

# ── Composer ───────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── Supervisor config ──────────────────────────────────────────────────
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/supervisor/conf.d/          /etc/supervisor/conf.d/
RUN mkdir -p /var/log/supervisor

# ── Create non-root user ───────────────────────────────────────────────
RUN useradd -G www-data,root -u ${uid} -d /home/${user} ${user} \
    && mkdir -p /home/${user}/.composer \
    && chown -R ${user}:${user} /home/${user}

# ── Entrypoint ─────────────────────────────────────────────────────────
COPY docker/php/entry.sh /usr/local/bin/entry.sh
RUN chmod +x /usr/local/bin/entry.sh

WORKDIR /var/www/html

# Entrypoint handles setup; CMD is passed to it and executed after
ENTRYPOINT ["/usr/local/bin/entry.sh"]
CMD ["supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
