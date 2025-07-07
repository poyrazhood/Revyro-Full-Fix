-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 03 Haz 2022, 23:21:04
-- Sunucu sürümü: 10.3.34-MariaDB-cll-lve
-- PHP Sürümü: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `database`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(225) NOT NULL,
  `bank_sube` varchar(225) NOT NULL,
  `bank_hesap` varchar(225) NOT NULL,
  `bank_iban` text DEFAULT NULL,
  `bank_alici` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `blog_title` text COLLATE utf8mb4_bin DEFAULT NULL,
  `blog_image` text CHARACTER SET utf8 DEFAULT NULL,
  `blog_content` text COLLATE utf8mb4_bin DEFAULT NULL,
  `blog_created` text CHARACTER SET utf8 NOT NULL,
  `url` varchar(225) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` text COLLATE utf8mb4_bin NOT NULL,
  `name_lang` text COLLATE utf8mb4_bin NOT NULL,
  `category_line` double NOT NULL,
  `category_type` enum('1','2') CHARACTER SET utf8 NOT NULL DEFAULT '2',
  `category_secret` enum('1','2') COLLATE utf8mb4_bin NOT NULL DEFAULT '2',
  `price_line` enum('1','0') COLLATE utf8mb4_bin DEFAULT '0',
  `icon` text COLLATE utf8mb4_bin DEFAULT NULL,
  `color` text COLLATE utf8mb4_bin DEFAULT NULL,
  `fiyat_siralama` enum('1','0') COLLATE utf8mb4_bin DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `child_panels`
--

CREATE TABLE `child_panels` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `panel_domain` text DEFAULT NULL,
  `panel_currency` text DEFAULT NULL,
  `panel_status` varchar(225) NOT NULL DEFAULT 'pending',
  `panel_price` text DEFAULT NULL,
  `panel_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cift_servis`
--

CREATE TABLE `cift_servis` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` text NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `first_name` varchar(225) DEFAULT NULL,
  `last_name` varchar(225) DEFAULT NULL,
  `email` varchar(225) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` text NOT NULL,
  `telephone` varchar(225) DEFAULT NULL,
  `balance` double NOT NULL DEFAULT 0,
  `balance_type` enum('1','2') NOT NULL DEFAULT '2',
  `debit_limit` double NOT NULL,
  `spent` double NOT NULL DEFAULT 0,
  `register_date` datetime NOT NULL,
  `login_date` datetime DEFAULT NULL,
  `login_ip` varchar(225) NOT NULL,
  `register_ip` varchar(225) NOT NULL,
  `apikey` text NOT NULL,
  `client_type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '2 -> ON, 1 -> OFF',
  `access` text DEFAULT NULL,
  `lang` varchar(255) NOT NULL DEFAULT 'tr',
  `timezone` double NOT NULL DEFAULT 0,
  `admin_theme` enum('1','2') NOT NULL DEFAULT '1',
  `referral` varchar(225) DEFAULT NULL,
  `referral_code` varchar(225) NOT NULL,
  `refchar` varchar(225) NOT NULL DEFAULT '0',
  `reforder` varchar(225) NOT NULL DEFAULT '0',
  `total_click` varchar(225) NOT NULL DEFAULT '0',
  `sms_verify` int(11) NOT NULL DEFAULT 1,
  `mail_verify` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `clients`
--

INSERT INTO `clients` (`client_id`, `first_name`, `last_name`, `email`, `username`, `password`, `telephone`, `balance`, `balance_type`, `debit_limit`, `spent`, `register_date`, `login_date`, `login_ip`, `register_ip`, `apikey`, `client_type`, `access`, `lang`, `timezone`, `admin_theme`, `referral`, `referral_code`, `refchar`, `reforder`, `total_click`, `sms_verify`, `mail_verify`) VALUES
(1, 'Glycon', 'Bilgi Teknolojileri', 'info@glycon.com', 'yonetici', 'd2da1e9f79c6ca6d5d60c7a2b8673c5a', '05555555555', 0, '1', 0, 0, '2021-11-04 12:08:28', '2022-06-03 23:16:58', '31.223.10.74', '', '99d8712f5476c0b329f7dfd9051fc12b', '2', '{\"admin_access\":\"1\",\"users\":\"1\",\"orders\":\"1\",\"subscriptions\":\"1\",\"dripfeed\":\"1\",\"tasks\":\"1\",\"services\":\"1\",\"payments\":\"1\",\"tickets\":\"1\",\"reports\":\"1\",\"general_settings\":\"1\",\"pages\":\"1\",\"blog\":\"1\",\"seo\":\"1\",\"menu\":\"1\",\"subject\":\"1\",\"child_panels\":\"1\",\"payments_settings\":\"1\",\"bank_accounts\":\"1\",\"payments_bonus\":\"1\",\"alert_settings\":\"1\",\"providers\":\"1\",\"modules\":\"1\",\"themes\":\"1\",\"language\":\"1\",\"logs\":\"1\",\"provider_logs\":\"1\",\"guard_logs\":\"1\",\"admins\":\"1\"}', 'tr', 0, '1', NULL, 'c6jHQy', '0', '0', '0', 2, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `clients_category`
--

CREATE TABLE `clients_category` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `clients_price`
--

CREATE TABLE `clients_price` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `clients_service`
--

CREATE TABLE `clients_service` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `client_favorite`
--

CREATE TABLE `client_favorite` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `services_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `client_report`
--

CREATE TABLE `client_report` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `report_ip` varchar(225) NOT NULL,
  `report_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cron_status`
--

CREATE TABLE `cron_status` (
  `id` int(11) NOT NULL,
  `cron_name` text NOT NULL,
  `status` set('0','1') NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `link` text DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `guard_log`
--

CREATE TABLE `guard_log` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `action` varchar(225) NOT NULL,
  `date` varchar(225) NOT NULL,
  `ip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `integrations`
--

CREATE TABLE `integrations` (
  `id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `description` varchar(225) NOT NULL,
  `icon_url` varchar(225) NOT NULL,
  `code` text NOT NULL,
  `visibility` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `integrations`
--

INSERT INTO `integrations` (`id`, `name`, `description`, `icon_url`, `code`, `visibility`, `status`) VALUES
(1, 'Beamer', 'Uygulama içi bildirim merkezi, widget\'lar ve değişiklik günlüğü ile güncellemeleri duyurun ve geri bildirim alın', '/img/integrations/Beamer.svg', '', 1, 1),
(2, 'Getsitecontrol', 'Web sitesi ziyaretçilerinin herhangi bir işlem yapmadan web sitenizden ayrılmalarını önlemenize yardımcı olur.', '/img/integrations/Getsitecontrol.svg', '', 2, 1),
(3, 'Google Analytics', 'Arama motoru optimizasyonu (SEO) ve pazarlama amaçları için istatistikler ve temel analitik araçlar', '/img/integrations/Google%20Analytics.svg', '', 2, 1),
(4, 'Google Tag manager', 'Basit etiket yönetimi çözümlerini kullanarak kodu düzenlemeden tüm web sitesi etiketlerinizi yönetin', '/img/integrations/Google%20Tag%20manager.svg', '', 1, 1),
(5, 'JivoChat', 'Müşterilerle konuşmak için hepsi bir arada iş habercisi: canlı sohbet, telefon, e-posta ve sosyal', '/img/integrations/JivoChat.svg', '', 1, 1),
(6, 'Onesignal', 'Müşteri etkileşiminde lider, mobil push, web push, e-posta, uygulama içi mesajları güçlendiriyor', '/img/integrations/Onesignal.svg', '', 1, 1),
(7, 'Push alert', 'Masaüstü ve mobil cihazlarda Push Bildirimleri ile erişimi, geliri artırın, kullanıcıları yeniden hedefleyin', '/img/integrations/Push%20alert.svg', '', 1, 1),
(8, 'Smartsupp', 'Tek bir müşteri mesajlaşma platformunda canlı sohbet, e-posta gelen kutusu ve Facebook Messenger', '/img/integrations/Smartsupp.svg', '', 1, 1),
(9, 'Tawk.to', 'Ziyaretçileri web sitenizde, mobil uygulamanızda veya ücretsiz özelleştirilebilir bir sayfadan izleyin ve sohbet edin', '/img/integrations/Tawk.to.svg', '', 1, 1),
(10, 'Tidio', 'Canlı sohbet, sohbet robotları, Messenger ve e-postaları tek bir yerde tutan işletmeler için Communicator', '/img/integrations/Tidio.svg', '', 1, 1),
(11, 'Zendesk Chat', 'Müşteri sorularına hızlı bir şekilde yanıt vermeye, bekleme sürelerini azaltmaya ve satışları artırmaya yardımcı olur', '/img/integrations/Zendesk%20Chat.svg', '', 1, 1),
(12, 'Getbutton.io', 'Popüler mesajlaşma uygulamaları aracılığıyla web sitesi ziyaretçileriyle sohbet edin. Whatsapp, messenger vb. iletişim butonu.', '/img/integrations/Getbutton.svg', '', 1, 1),
(13, 'Google reCAPTCHA v2', 'Kötü amaçlı yazılımların web sitenizde kötüye kullanım faaliyetlerine girmesini önlemek için gelişmiş bir risk analizi motoru ve uyarlanabilir zorluklar kullanır.', '/img/integrations/reCAPTCHA.svg', '', 1, 1),
(14, 'SEO Ayarlamaları', 'Arama Motoru Optimizasyonu (SEO), Web sitelerinin arama motorlarında daha iyi performans göstermesi için yapılan çalışmaların tümüne verilen isimdir.', '/img/integrations/Seo settings.png', '', 1, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `language_name` varchar(225) NOT NULL,
  `language_code` varchar(225) NOT NULL,
  `language_type` enum('2','1') NOT NULL DEFAULT '2',
  `default_language` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `languages`
--

INSERT INTO `languages` (`id`, `language_name`, `language_code`, `language_type`, `default_language`) VALUES
(1, 'Türkçe', 'tr', '2', '1'),
(13, 'English', 'en', '1', '0');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(225) CHARACTER SET utf8 NOT NULL,
  `tag` varchar(225) CHARACTER SET utf8 NOT NULL,
  `link` text NOT NULL,
  `icon` text NOT NULL DEFAULT '',
  `status` int(11) NOT NULL,
  `public` int(11) NOT NULL,
  `edit` int(11) NOT NULL,
  `line` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `menu`
--

INSERT INTO `menu` (`id`, `name`, `tag`, `link`, `icon`, `status`, `public`, `edit`, `line`) VALUES
(2, 'API', 'api', '', '', 2, 2, 0, 1),
(3, 'Kullanıcı Sözleşmesi', 'terms', '', 'adasd', 1, 1, 0, 2),
(4, 'Sıkça Sorulan Sorular', 'faq', '', 'adasd', 1, 1, 0, 3),
(5, 'Blog', 'blog', '', '', 2, 2, 0, 4),
(6, 'Bize Ulaşın', 'contact', '', 'contact', 1, 1, 0, 5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2021-11-09-073319', 'App\\Database\\Migrations\\Settings', 'default', 'App', 1636443634, 1),
(2, '2021-11-09-074127', 'App\\Database\\Migrations\\Settings', 'default', 'App', 1636444447, 2),
(3, '2021-11-09-073343', 'App\\Database\\Migrations\\Popup', 'default', 'App', 1636444498, 3),
(4, '2021-11-09-080359', 'App\\Database\\Migrations\\Settings', 'default', 'App', 1636445074, 4),
(5, '2021-11-12-041927', 'App\\Database\\Migrations\\Categories', 'default', 'App', 1636690905, 5),
(11, '2021-11-23-104635', 'App\\Database\\Migrations\\ServiceReport', 'default', 'App', 1637679885, 6),
(12, '2021-12-01-153604', 'App\\Database\\Migrations\\Services', 'default', 'App', 1638373137, 7),
(13, '2021-12-09-054506', 'App\\Database\\Migrations\\Categories', 'default', 'App', 1641867682, 8);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `ajax_name` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 2,
  `mod_sec` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `modules`
--

INSERT INTO `modules` (`id`, `name`, `description`, `ajax_name`, `status`, `mod_sec`) VALUES
(1, 'Referans Sistemi', 'Mevcut kullanıcılar yeni kullanıcıları davet eder ve tüm ödemelerinden komisyon alır. Kullanıcılar, minimum ödemeye ulaştıklarında ödeme talep edebilir.', 'module_ref', 2, 1),
(2, 'Child panel satışı', 'Yalnızca sizden API çekebilen, sınırlı özelliklere sahip bir panel. Kullanıcılar panelinizden child paneller sipariş edebilir.', 'module_child', 2, 1),
(3, 'Ücretsiz Bakiye', 'Yeni kayıt olan üyeler için bir defalık ücretsiz otomatik bakiye.', 'module_balance', 1, 1),
(4, 'Destek Sistemi', 'Ekleyeceğiniz başıklara gireceğiniz hazır cevaplar, yeni destek talebi oluşturan müşterilere otomatik olarak gönderilir.', '', 2, 1),
(6, 'Guard (Koruma)', '7/24 Maksimum güvenlik, tüm faaliyetleri sınırlandırın! Saldırılara karşı %100 koruma.', 'module_guard', 1, 2),
(7, 'Cache (Önbellekleme)', 'Sitenin kaynak tüketimi azaltılarak site hızını arttırır daha verimli bir sistem ortaya çıkarmak amaçlanır.', 'module_cache', 1, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `news_icon` varchar(225) NOT NULL,
  `news_title` varchar(225) NOT NULL,
  `news_content` varchar(225) NOT NULL,
  `news_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `api_orderid` int(11) NOT NULL DEFAULT 0,
  `order_error` text NOT NULL,
  `order_detail` text DEFAULT NULL,
  `api_orderid2` int(11) NOT NULL DEFAULT 0,
  `order_error2` text NOT NULL DEFAULT '',
  `order_detail2` text NOT NULL DEFAULT '',
  `order_api` int(11) NOT NULL DEFAULT 0,
  `api_serviceid` int(11) NOT NULL DEFAULT 0,
  `api_charge` double DEFAULT NULL,
  `api_currencycharge` double NOT NULL DEFAULT 1,
  `order_profit` double NOT NULL,
  `order_quantity` double NOT NULL,
  `order_extras` text NOT NULL,
  `order_charge` double DEFAULT NULL,
  `dripfeed` enum('1','2','3') DEFAULT '1' COMMENT '2 -> ON, 1 -> OFF',
  `dripfeed_id` double NOT NULL DEFAULT 0,
  `subscriptions_id` double NOT NULL DEFAULT 0,
  `subscriptions_type` enum('1','2') NOT NULL DEFAULT '1' COMMENT '2 -> ON, 1 -> OFF',
  `dripfeed_totalcharges` double DEFAULT NULL,
  `dripfeed_runs` double DEFAULT NULL,
  `dripfeed_delivery` double NOT NULL DEFAULT 0,
  `dripfeed_interval` double DEFAULT NULL,
  `dripfeed_totalquantity` double DEFAULT NULL,
  `dripfeed_status` enum('active','completed','canceled') NOT NULL DEFAULT 'active',
  `order_url` text NOT NULL,
  `order_start` double NOT NULL DEFAULT 0,
  `order_finish` double NOT NULL DEFAULT 0,
  `order_remains` double NOT NULL DEFAULT 0,
  `order_create` datetime NOT NULL,
  `order_status` enum('pending','inprogress','completed','partial','processing','canceled') NOT NULL DEFAULT 'pending',
  `instagram_id` varchar(255) NOT NULL,
  `subscriptions_status` enum('active','paused','completed','canceled','expired','limit') NOT NULL DEFAULT 'active',
  `subscriptions_username` text DEFAULT NULL,
  `subscriptions_posts` double DEFAULT NULL,
  `subscriptions_delivery` double NOT NULL DEFAULT 0,
  `subscriptions_delay` double DEFAULT NULL,
  `subscriptions_min` double DEFAULT NULL,
  `subscriptions_max` double DEFAULT NULL,
  `subscriptions_expiry` date DEFAULT NULL,
  `last_check` datetime NOT NULL,
  `refill_check` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_where` enum('site','api') NOT NULL DEFAULT 'site'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pages`
--

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL,
  `page_name` varchar(225) NOT NULL,
  `page_get` varchar(225) NOT NULL,
  `page_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `pages`
--

INSERT INTO `pages` (`page_id`, `page_name`, `page_get`, `page_content`) VALUES
(1, 'Giriş Yap', 'auth', ''),
(2, 'Servisler', 'services', ''),
(3, 'Sıkça Sorulan Sorular', 'faq', '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px;\"><b>Sosyal Medya Boost Nedir?</b></span><br></p><p>Sosyal Medya Boost demek oluyor ki panelimizin dilimize uyarladığı terimiyle “Sanalizasyon”. Sanalizasyon; sosyal medya hesaplarının belirli bir kitleye sahip olduğunu varsayım olarak göstermek anlamına gelir. Varsayım (Sanalizasyon) olarak gösterilen profillerin görsel algı konusunu göze alarak, sosyal medya hesaplarının ulaşmak istedikleri hedeflere daha kolay ulaşabilmesi uygulamalarımızca her gün kanıtlanmaktadır</p><p><span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\"><b>Hesabım Risk Altında Mı?</b></span></p><p>Panelimiz üzerinden verilen siparişlerin sosyal medya hesaplarına olan etkisini an ve an kayıt ediyor ve gözlemliyoruz. Bilmenizi isteriz ki panelimiz uygulamaya girdiği tarihten itibaren, kullanıcılarımız tarafından kötüye kullanım olmadığı sürece hesaplarda herhangi bir risk gözlemlemedik. Fakat oluşacak herhangi bir problemden dolayı panelimizin hiçbir sorumluluğu almadığını bilmenizi isteriz.</p><p><span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\"><b>Hesabıma Etkisi Nedir?</b></span></p><p>Panelimiz üzerinden vereceğiniz siparişlerde kontrollü ve düzenli bir şekilde ilerleme izlerseniz etkisini göreceğinizden emin olabilirsiniz. Birkaç örnek ile açıklamamız gerekirse; Instagram üzerinden paylaştığınız gönderiler “Keşfet” bölümüne çıkar. Bu, sizin gönderinizin daha fazla Instagram kullanıcısına ulaşmasınız sağlar. Bir diğer örnek, Youtube üzerinden paylaştığınız videolarda “Önerilenler” bölümünde sürekli gözükme olasılığınız artar. Bu, sizin video paylaşımınızın daha fazla Youtube kullanıcı tarafından görülmesine yardımcı olur.</p><p><span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\"><b>Destek Talebi Nasıl Oluşturulur?</b></span></p><p>Masaüstü bir bilgisayar ile giriş yapıyorsanız sol tarafta bulunan menüden “Destek Merkezi” simgesine tıklayın, eğer mobil ile giriş yapıyorsanız sağ üst tarafta bulunan menü butonuna tıklayın ardından “Destek Merkezi” yazan butona tıklayın. Açılan sayfada destek talebi oluşturmak istediğiniz konu seçimini yapın. Daha sonra gerekli alanları doldurarak “Destek Talebi Oluştur” butonuna tıklayın.</p><p><span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\"><b>Nasıl Bakiye Yüklerim?</b></span></p><p>Panelimiz üzerinden kayıt işlemini başarılı bir şekilde gerçekleştirdikten sonra masaüstü bir bilgisayar ile giriş yapıyorsanız sol tarafta bulunan menüden “Bakiye Ekle” simgesine tıklayın, eğer mobil ile giriş yapıyorsanız sağ üst tarafta bulunan menü butonuna tıklayın ardından “Bakiye Ekle” yazan butona tıklayın. Bu sayfadan PayTR ile 7/24 olarak komisyonsuz ödeme yapabilirsiniz. Kart bilgileriniz hiçbir şekilde panelimiz alt yapısında bulunmamaktadır. PayTR ile yaptığınız ödemeler 3D secure sistemi ile korunmaktadır.</p><p><b><span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\">Nasıl Sipariş Verebilirim?</span><span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\"><br></span></b>Masaüstü bir bilgisayar ile giriş yapıyorsanız sol tarafta bulunan menüden “Yani Sipariş” simgesine tıklayın, eğer mobil ile giriş yapıyorsanız sağ üst tarafta bulunan menü butonuna tıklayın ardından “Yeni Sipariş” yazan butona tıklayın. Açılan sayfada “Kategoriler” kısmında panelimize ait tüm servisler yer almaktadır. Bu menüden sipariş vermek istediğiniz kategoriyi seçin. Daha sonra “Servis” menüsünden hangi servisten sipariş vermek istiyorsanız o servisi seçin. Seçtiğiniz serviste yazan fiyat 1000 adet fiyattır. “Servis Açıklaması” kısmını detaylı olarak okuduğunuza emin olduktan sonra “Link” yazan yere açıklamada belirtilen link türünü yazın. Daha sonra miktar bölümüne o servisten verebileceğiniz maksimum veya minimum değerler arasında bir miktar yazın. Bu işlemleri yaptıktan sonra “Sipariş Ver” butonuna tıklayın. Siparişiniz açıklama kısmında belirtilen süre içerisinde tamamlanacaktır.<span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\"><b><br></b></span><span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\"><b><br></b></span><span style=\"color: rgb(21, 23, 34); font-family: Poppins, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Oxygen, Ubuntu, Cantarell, &quot;Open Sans&quot;, &quot;Helvetica Neue&quot;, sans-serif; font-size: 20px; background-color: rgb(255, 255, 255);\"><b><br></b></span><br></p>'),
(4, 'Sözleşmeler', 'terms', '<p><b>Taraflar</b></p><p>İşbu Kullanıcı Sözleşmesi (“Sözleşme”) bu siteye kullanıcı olarak kaydolan kişi arasında akdedilmektedir. Sözleşme, kullanıcının elektronik ortamda kabulü ile yürürlüğe girmekle birlikte, Sözleşmede yer alan usuller kapsamında sonlandırılmadığı sürece yürürlükte kalmaya devam edecektir.</p><p><b>Sözleşmenin Konusu ve Kapsamı</b></p><p>İşbu Sözleşme, sitemiz’den alacağınız her türlü hizmetin genel kullanım şartlarını, genel kurallarını ve tarafların yasal sorumluluklarını düzenlemektedir. Site’ye üyelik ve kullanım öncesinde sözleşme okunmalı, belirtilen şartların kullanıcı iradesine uygun olmaması halinde Site kullanılmamalıdır. Kullanıcı Site’ye kaydolarak, kişisel bilgilerinin yer alacağı formu doldurarak veya siteyi kullanarak yazılı şartları kabul etmiş sayılır. sitemiz her tür hizmet, ürün bilgisi; kullanma koşulları ve sitede yer alan içeriği önceden bir ihtira gerek olmasınızın değiştirme, yeniden oluşturma, yayını durdurma hakkını saklı tutar. Sözleşmede yapılan tüm değişiklikler, sitede yayın tarihiyle birlikte yürürlüğe girer. Bu site, yalnızca online hizmet vermekte olup, anlık (kargosuz - online) teslimat sağlamaktadır. Herhangi bir sebeple tamamlanamayan hizmet, kullanıcıya bilgi verilerek asgari 30 gün içerisinde tamamlanır. Tamamlanamaması halinde, hizmet iptali gerçekleştirilip ücret iadesi sağlanır. Kullanıcının, uygun düştüğü ölçüde Tüketicinin Korunması Hakkında Kanun’dan doğan hakları saklıdır.</p><p><b>Genel Kullanım</b></p><p>Site üzerinden verilen hizmetler aksi belirtilmedikçe ücretlidir. Yasal zorunluluklarda veya aşağıda geçen durumlarda, kullanıcının site kullanımı engellenebilir; sayılanları gerçekleştiren, teşebbüs eden, iştirak eden kişi/kişiler hakkında, kanuni haklarını saklı tutar:</p><p>Yanlış, düzensiz, eksik ve yanıltıcı bilgiler, genel ahlak kurallarına uygun olmayan ifadeler içeren ve Türkiye Cumhuriyeti yasalarıyla ters düşen bilgilerin, içeriklerin siteye kaydedilmesi,</p><p>Site içeriğinin kısmen veya tümüyle kopyalanması,</p><p>Kullanıcıların kullanıcı adı, şifre gibi bilgilerini; kullanım haklarını üçüncü kişilerle paylaşması (kullanıcı haricinde sistemin kullanılması dahil) ve sonucunda doğan her türlü zarardan doğrudan kullanıcı sorumludur. Bununla birlikte,kullanıcı başkasına ait kimlik bilgileri, elektronik posta adresi, IP adresi kullanamayacağı gibi diğer kullanıcıların özel bilgilerine ulaşamaz, bunları kullanamaz. Aksi halde kullanıcı doğabilecek her türlü hukuki ve cezai yükümlülüğü kabul etmiş sayılır.</p><p>diğer kullanıcıların güvenliğini tehdit edecek, site’nin çalışmasını bozacak, tehlikeye düşürecek, engelleyecek yazılımların kullanılması, faaliyetlerin yapılması, yapılmaya çalışılması, teşebbüste bulunulması veya bilgilerin alınması, silinmesi, değiştirilmesi.</p><p><b>Kullanım</b></p><p>Kullanıcı ve ziyaretçiler site’nin kullanılmasını engelleyecek, zorlaştırıcı hiçbir harekette bulunamaz, otomatik programlar vasıtasıyla zorlayamaz, hile girişimlerinde bulunamaz. Aksi halde üyeliğinin sonlandırılacağını, doğabilecek her türlü hukuki ve cezai sorumluluğu kabul eder. Site ile yapılan mesajlaşmaların yedeklenmesi kişilerin sorumluluğundadır ve her türlü mağduriyetin önüne geçilebilmesi için tavsiye edilmektedir. Mesaj içeriklerinin kaybolması, silinmesi veya hasar görmesinden Site sorumlu tutulamaz. Kullanıcı silme veye üyelik iptali işlemi, kullanıcı tarafından destek talebi oluşturularak gerçekleştirilebilir. Bu işlemi talep eden kullanıcı geri dönüşü olmadığını kabul ederek, siteye giriş yetkisini kaybeder. Bununla birlikte Site, silinmesini talep eden kullanıcıya ait her türlü kaydı silmeme kararı alabilir. Silinen hesaplarla ilgili kullanıcı hiçbir hak ve tazminat talebinde bulunamaz. Site’nin kullanıcı kayıt olurken verdiği tüm iletişim kanallarından, aksine bir yazılı bildirimi olmadığı müddetçe iletişim, pazarlama, bildirim ve diğer amaçlarla kullanıcıya ulaşma hakkı bulunmaktadır. Kullanıcı sözleşmeyi kabul etmekle aksine yazılı bildirimi bulunmadığı sürece, Site’nin kendisine yönelik yukarıda belirtilen iletişim faaliyetlerinde bulunabileceğini kabul ve beyan etmektedir. Tüm bunlarla birlikte, Site belirli hizmetleri için farklı kural ve yükümlülükler belirleyebilir. Bu hizmetleri kullanan kullanıcılar peşinen kabul etmiş sayılır. Site’nin ilgili mevzuatlardan doğan tüm hakları saklıdır. Aksi davranışlar hukuki ve cezai sorumluluk doğurmaktadır.</p><p><b>Hizmet Sürekliliği</b></p><p>Site, verdiği hizmetin sürekliliğini sağlayabilmek için tek taraflı olarak tebliğe gerek olmaksınız Sözleşme’de değişiklikler yapabilir; her zaman ve gerekçe göstermeksizin hizmeti sürekli veya geçici olarak durdurma, servisin içeriğini değiştirme veya iptal etme hakkı vardır. Yapılan bu değişiklikler, yayınlandığı andan itibaren hüküm ve sonuç doğuracak, bu sayfada tarihi ile birlikte güncellenecek, gerekli görülmesi halinde kullanıcılara e-posta veya sms yoluyla bildirilecektir. Fesih sebeplerinden herhangi birinin gerçekleştiği andan itibaren Site’nin kullanıcıya karşı sorumluluğu da hiçbir bildirime gerek olmaksızın son bulur.</p><p><b>Gizlilik</b></p><p>Site’yi ziyaret eden kullanıcıların bilgileri, daha iyi hizmet verebilmek amacıyla takip edilebilir. Kullanıcıların Site üzerinden yaptığı işlemler ile hizmet içeriği gizli tutulur ve 3. şahıslarla paylaşılmaz. Kullanıcı tarafından verilen siparişler servis sağlayıcıları ile paylaşılmaktadır. Ancak resmi devlet kurumlarından talep edilmesi halinde, kayıtlar resmi devlet kurumu görevlilerine sunulabilir. Ödeme sayfasında gereken kredi kartı bilgileri, siteye bakiye yükleyen kullanıcıların güvenliğini en üst seviyede tutmak amacıyla hiçbir şekilde Site tarafından ve hizmet veren şirketlerin sunucularında tutulmaz. Bu sebeple ödeme işlemleri Site arayüzüyle; banka, online ödeme gibi sistemler ile bilgisayarınız ya da telefonunuz arasında gerçekleşir. Kullanıcıların sisteme girdikleri bilgilere kendileri ulaşabilmekle birlikte yine kendileri değişiklik yapabilmektedir. Bir başka kullanıcının, bu bilgilere erişebilmesine ve değişiklik yapabilmesine hiçbir şekilde izin verilmez.İşbu Kullanıcı Sözleşmesi (“Sözleşme”) bu siteye kullanıcı olarak kaydolan kişi arasında akdedilmektedir. Sözleşme, kullanıcının elektronik ortamda kabulü ile yürürlüğe girmekle birlikte, Sözleşmede yer alan usuller kapsamında sonlandırılmadığı sürece yürürlükte kalmaya devam edecektir.\r\n\r\n\r\n\r\nSözleşmenin Konusu ve Kapsamı\r\n\r\n\r\n\r\nİşbu Sözleşme, sitemiz’den alacağınız her türlü hizmetin genel kullanım şartlarını, genel kurallarını ve tarafların yasal sorumluluklarını düzenlemektedir. Site’ye üyelik ve kullanım öncesinde sözleşme okunmalı, belirtilen şartların kullanıcı iradesine uygun olmaması halinde Site kullanılmamalıdır. Kullanıcı Site’ye kaydolarak, kişisel bilgilerinin yer alacağı formu doldurarak veya siteyi kullanarak yazılı şartları kabul etmiş sayılır. sitemiz her tür hizmet, ürün bilgisi; kullanma koşulları ve sitede yer alan içeriği önceden bir ihtira gerek olmasınızın değiştirme, yeniden oluşturma, yayını durdurma hakkını saklı tutar. Sözleşmede yapılan tüm değişiklikler, sitede yayın tarihiyle birlikte yürürlüğe girer. Bu site, yalnızca online hizmet vermekte olup, anlık (kargosuz - online) teslimat sağlamaktadır. Herhangi bir sebeple tamamlanamayan hizmet, kullanıcıya bilgi verilerek asgari 30 gün içerisinde tamamlanır. Tamamlanamaması halinde, hizmet iptali gerçekleştirilip ücret iadesi sağlanır. Kullanıcının, uygun düştüğü ölçüde Tüketicinin Korunması Hakkında Kanun’dan doğan hakları saklıdır.\r\n\r\n\r\n\r\nGenel Kullanım\r\n\r\nSite üzerinden verilen hizmetler aksi belirtilmedikçe ücretlidir. Yasal zorunluluklarda veya aşağıda geçen durumlarda, kullanıcının site kullanımı engellenebilir; sayılanları gerçekleştiren, teşebbüs eden, iştirak eden kişi/kişiler hakkında, kanuni haklarını saklı tutar:\r\n\r\n\r\n\r\nYanlış, düzensiz, eksik ve yanıltıcı bilgiler, genel ahlak kurallarına uygun olmayan ifadeler içeren ve Türkiye Cumhuriyeti yasalarıyla ters düşen bilgilerin, içeriklerin siteye kaydedilmesi,\r\n\r\n\r\n\r\nSite içeriğinin kısmen veya tümüyle kopyalanması,\r\n\r\n\r\n\r\nKullanıcıların kullanıcı adı, şifre gibi bilgilerini; kullanım haklarını üçüncü kişilerle paylaşması (kullanıcı haricinde sistemin kullanılması dahil) ve sonucunda doğan her türlü zarardan doğrudan kullanıcı sorumludur. Bununla birlikte,kullanıcı başkasına ait kimlik bilgileri, elektronik posta adresi, IP adresi kullanamayacağı gibi diğer kullanıcıların özel bilgilerine ulaşamaz, bunları kullanamaz. Aksi halde kullanıcı doğabilecek her türlü hukuki ve cezai yükümlülüğü kabul etmiş sayılır.\r\n\r\n\r\n\r\ndiğer kullanıcıların güvenliğini tehdit edecek, site’nin çalışmasını bozacak, tehlikeye düşürecek, engelleyecek yazılımların kullanılması, faaliyetlerin yapılması, yapılmaya çalışılması, teşebbüste bulunulması veya bilgilerin alınması, silinmesi, değiştirilmesi.\r\n\r\n\r\n\r\nKullanım\r\n\r\nKullanıcı ve ziyaretçiler site’nin kullanılmasını engelleyecek, zorlaştırıcı hiçbir harekette bulunamaz, otomatik programlar vasıtasıyla zorlayamaz, hile girişimlerinde bulunamaz. Aksi halde üyeliğinin sonlandırılacağını, doğabilecek her türlü hukuki ve cezai sorumluluğu kabul eder. Site ile yapılan mesajlaşmaların yedeklenmesi kişilerin sorumluluğundadır ve her türlü mağduriyetin önüne geçilebilmesi için tavsiye edilmektedir. Mesaj içeriklerinin kaybolması, silinmesi veya hasar görmesinden Site sorumlu tutulamaz. Kullanıcı silme veye üyelik iptali işlemi, kullanıcı tarafından destek talebi oluşturularak gerçekleştirilebilir. Bu işlemi talep eden kullanıcı geri dönüşü olmadığını kabul ederek, siteye giriş yetkisini kaybeder. Bununla birlikte Site, silinmesini talep eden kullanıcıya ait her türlü kaydı silmeme kararı alabilir. Silinen hesaplarla ilgili kullanıcı hiçbir hak ve tazminat talebinde bulunamaz. Site’nin kullanıcı kayıt olurken verdiği tüm iletişim kanallarından, aksine bir yazılı bildirimi olmadığı müddetçe iletişim, pazarlama, bildirim ve diğer amaçlarla kullanıcıya ulaşma hakkı bulunmaktadır. Kullanıcı sözleşmeyi kabul etmekle aksine yazılı bildirimi bulunmadığı sürece, Site’nin kendisine yönelik yukarıda belirtilen iletişim faaliyetlerinde bulunabileceğini kabul ve beyan etmektedir. Tüm bunlarla birlikte, Site belirli hizmetleri için farklı kural ve yükümlülükler belirleyebilir. Bu hizmetleri kullanan kullanıcılar peşinen kabul etmiş sayılır. Site’nin ilgili mevzuatlardan doğan tüm hakları saklıdır. Aksi davranışlar hukuki ve cezai sorumluluk doğurmaktadır.\r\n\r\n\r\n\r\nHizmet Sürekliliği\r\n\r\nSite, verdiği hizmetin sürekliliğini sağlayabilmek için tek taraflı olarak tebliğe gerek olmaksınız Sözleşme’de değişiklikler yapabilir; her zaman ve gerekçe göstermeksizin hizmeti sürekli veya geçici olarak durdurma, servisin içeriğini değiştirme veya iptal etme hakkı vardır. Yapılan bu değişiklikler, yayınlandığı andan itibaren hüküm ve sonuç doğuracak, bu sayfada tarihi ile birlikte güncellenecek, gerekli görülmesi halinde kullanıcılara e-posta veya sms yoluyla bildirilecektir. Fesih sebeplerinden herhangi birinin gerçekleştiği andan itibaren Site’nin kullanıcıya karşı sorumluluğu da hiçbir bildirime gerek olmaksızın son bulur.\r\n\r\n\r\n\r\nGizlilik\r\n\r\nSite’yi ziyaret eden kullanıcıların bilgileri, daha iyi hizmet verebilmek amacıyla takip edilebilir. Kullanıcıların Site üzerinden yaptığı işlemler ile hizmet içeriği gizli tutulur ve 3. şahıslarla paylaşılmaz. Kullanıcı tarafından verilen siparişler servis sağlayıcıları ile paylaşılmaktadır. Ancak resmi devlet kurumlarından talep edilmesi halinde, kayıtlar resmi devlet kurumu görevlilerine sunulabilir. Ödeme sayfasında gereken kredi kartı bilgileri, siteye bakiye yükleyen kullanıcıların güvenliğini en üst seviyede tutmak amacıyla hiçbir şekilde Site tarafından ve hizmet veren şirketlerin sunucularında tutulmaz. Bu sebeple ödeme işlemleri Site arayüzüyle; banka, online ödeme gibi sistemler ile bilgisayarınız ya da telefonunuz arasında gerçekleşir. Kullanıcıların sisteme girdikleri bilgilere kendileri ulaşabilmekle birlikte yine kendileri değişiklik yapabilmektedir. Bir başka kullanıcının, bu bilgilere erişebilmesine ve değişiklik yapabilmesine hiçbir ş</p><p><b>İade Politikası</b></p><p>Cayma hakkı süresi sona ermeden önce, tüketicinin onayı ile hizmetin ifasına başlanan hizmet sözleşmelerinde kullanıcı cayma hakkını kullanamaz. Siparişler sisteme girildikten sonra sonuçlanmadan iptal/iade talebiniz kabul edilmeyecektir. Eğer sipariş tamamlanmazsa / kısmen tamamlanırsa sistem otomatik olarak geri ödeme yapacaktır. Tekrar sipariş vermeden iade talep edebilirsiniz. Site beğeni, takipçi, izlenme vesair hizmetlerin kalıcı olacağını garanti ve beyan etmediği gibi, düşüş yaşanabileceğini net olarak belirmektedir.Düşüşlere karşı 30 gün telafili hizmetler kullanıcılara sunulmuştur. Bu hizmetlerin dışında hiçbir sorumluluk kabul etmez. Düşüşler yaşandıktan sonra herhangi bir söz hakkına sahip olmamakla birlikte şikayet hakkınızda bulunmamaktadır. Sipariş sırasında hesap gizliliği, kullanıcı adı değişikliği vb durumlarda servis sağlayıcısı iade etmediği sürece Site tarafından herhangi bir iade söz konusu değildir.</p><p></p>'),
(5, 'Yeni Sipariş', 'neworder', ''),
(6, 'Bakiye Ekle', 'addfunds', ''),
(7, 'Child panels', 'child-panels', ''),
(8, 'Destek', 'tickets', ''),
(9, 'Davet Et Kazan', 'affiliates', ''),
(10, 'Bize Ulaşın', 'contact', '<p><span style=\"font-size: 18pt;\"><strong>Kurumsal Bilgilerimiz ;&nbsp;</strong></span><br /><strong>Ad Soyad:</strong> <br /><strong>Adres:</strong> <br /><strong>Telefon:</strong></p>');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `paket_kategori`
--

CREATE TABLE `paket_kategori` (
  `id` int(11) NOT NULL,
  `platform` set('0','1') NOT NULL DEFAULT '0',
  `platform_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `client_balance` double NOT NULL DEFAULT 0,
  `payment_amount` double NOT NULL,
  `papara_amount` double DEFAULT NULL,
  `payment_privatecode` varchar(100) DEFAULT NULL,
  `payment_method` int(11) NOT NULL,
  `payment_status` enum('1','2','3') NOT NULL DEFAULT '1',
  `payment_delivery` enum('1','2') NOT NULL DEFAULT '1',
  `payment_note` text NOT NULL,
  `payment_mode` enum('Manuel','Otomatik') NOT NULL DEFAULT 'Otomatik',
  `payment_create_date` datetime NOT NULL,
  `payment_update_date` datetime NOT NULL,
  `payment_ip` varchar(225) NOT NULL,
  `payment_extra` text NOT NULL,
  `payment_bank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payments_bonus`
--

CREATE TABLE `payments_bonus` (
  `bonus_id` int(11) NOT NULL,
  `bonus_method` int(11) NOT NULL,
  `bonus_from` double NOT NULL,
  `bonus_amount` double NOT NULL,
  `bonus_type` enum('1','2') NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `method_name` varchar(225) NOT NULL,
  `method_get` varchar(225) NOT NULL,
  `method_min` double NOT NULL,
  `method_max` double NOT NULL,
  `method_type` enum('1','2') NOT NULL DEFAULT '2' COMMENT '2 -> ON, 1 -> OFF	',
  `method_extras` text NOT NULL,
  `method_line` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `method_name`, `method_get`, `method_min`, `method_max`, `method_type`, `method_extras`, `method_line`) VALUES
(1, 'Paytr', 'paytr', 1, 0, '1', '{\"method_type\":\"1\",\"name\":\"Paytr\",\"min\":\"1\",\"max\":\"0\",\"merchant_id\":\"\",\"merchant_key\":\"\",\"merchant_salt\":\"\",\"fee\":\"0\"}', 1),
(2, 'Paytrhavale', 'paytr_havale', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"Paytrhavale\",\"min\":\"1\",\"max\":\"0\",\"merchant_id\":\"\",\"merchant_key\":\"\",\"merchant_salt\":\"\",\"fee\":\"0\"}', 2),
(3, 'Paywant', 'paywant', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"Paywant\",\"min\":\"1\",\"max\":\"0\",\"apiKey\":\"\",\"apiSecret\":\"\",\"fee\":\"0\",\"commissionType\":\"2\",\"payment_type\":[\"1\",\"2\",\"3\"]}', 3),
(4, 'Shopier', 'shopier', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"Shopier\",\"min\":\"1\",\"max\":\"\",\"apiKey\":\"\",\"apiSecret\":\"\",\"website_index\":\"1\",\"processing_fee\":\"1\",\"fee\":\"4,99\"}', 4),
(5, 'Shoplemo', 'shoplemo', 1, 0, '1', '{\"method_type\":\"1\",\"name\":\"Shoplemo\",\"min\":\"1\",\"max\":\"0\",\"apiKey\":\"\",\"apiSecret\":\"\",\"fee\":\"0\"}', 5),
(6, 'CoinPayments', 'coinpayments', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"Coinpayments\",\"min\":\"1\",\"max\":\"0\",\"coinpayments_public_key\":\"\",\"coinpayments_private_key\":\"\",\"coinpayments_currency\":\"\",\"merchant_id\":\"\",\"ipn_secret\":\"\",\"fee\":\"\"}', 7),
(7, 'Banka Ödemeleri', 'havale-eft', 0, 0, '1', '{\"method_type\":\"1\",\"name\":\"Banka \\u00d6demeleri\"}', 10),
(9, '2checkout', '2checkout', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"2checkout\",\"min\":\"1\",\"max\":\"0\",\"seller_id\":\"\",\"private_key\":\"\",\"currency\":\"\",\"fee\":\"\"}', 8),
(12, 'PayTM', 'paytm', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"Paytm\",\"min\":\"1\",\"max\":\"0\",\"merchant_key\":\"\",\"merchant_mid\":\"\",\"merchant_website\":\"\",\"currency\":\"INR\",\"fee\":\"\"}', 9),
(13, 'Weepay', 'weepay', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"Weepay\",\"min\":\"1\",\"max\":\"0\",\"bayi_id\":\"\",\"api_key\":\"\",\"secret_key\":\"\",\"currency\":\"USD\",\"fee\":\"0\"}', 6),
(15, 'Payizone', 'payizone', 0, 0, '1', '{\"method_type\":\"1\",\"name\":\"Payizone\",\"min\":\"0\",\"max\":\"0\",\"appid\":\"\",\"apiSecret\":\"\",\"email\":\"\",\"otherCode\":\"\",\"fee\":\"0\"}', 15),
(16, 'Payeer', 'payeer', 0, 0, '1', '{\"method_type\":\"1\",\"name\":\"Payeer\",\"min\":\"0\",\"max\":\"0\",\"mshop\":\"\",\"mkey\":\"\",\"lang\":\"en\",\"currency\":\"USD\",\"fee\":\"0\"}', 16),
(17, 'PayHesap', 'payhesap', 0, 0, '1', '{\"method_type\":\"1\",\"name\":\"PayHesap\",\"min\":\"0\",\"max\":\"0\",\"apiKey\":\"\",\"currency\":\"TRY\",\"fee\":\"0\"}', 17),
(18, 'Perfect Money', 'perfectmoney', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"Perfect Money\",\"min\":\"1\",\"max\":\"0\",\"aid\":\"\",\"pid\":\"\",\"current\":\"USD\"}', 18),
(19, 'Paypal', 'paypal', 1, 0, '1', '{\"method_type\":\"2\",\"name\":\"Paypal\",\"min\":\"1\",\"max\":\"0\",\"client\":\"\",\"secret\":\"\",\"charge\":\"USD\"}', 11);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `popup`
--

CREATE TABLE `popup` (
  `id` int(5) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `icerik` text DEFAULT NULL,
  `tur` int(11) DEFAULT NULL,
  `zaman` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `proxy`
--

CREATE TABLE `proxy` (
  `id` int(11) NOT NULL,
  `user` varchar(225) CHARACTER SET utf8 NOT NULL,
  `pass` varchar(225) CHARACTER SET utf8 NOT NULL,
  `ip` varchar(225) CHARACTER SET utf8 DEFAULT NULL,
  `port` varchar(225) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tablo döküm verisi `proxy`
--

INSERT INTO `proxy` (`id`, `user`, `pass`, `ip`, `port`) VALUES
(1, 'proxydeactive', 'proxydeactive', 'proxydeactive', 'proxydeactive');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `referral`
--

CREATE TABLE `referral` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `refferal` int(11) NOT NULL,
  `action` text CHARACTER SET utf8 NOT NULL,
  `register_date` varchar(225) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reset_log`
--

CREATE TABLE `reset_log` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `token` varchar(225) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `serviceapi_alert`
--

CREATE TABLE `serviceapi_alert` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `serviceapi_alert` text NOT NULL,
  `servicealert_extra` text NOT NULL,
  `servicealert_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_api` int(11) NOT NULL DEFAULT 0,
  `api_service` int(11) NOT NULL DEFAULT 0,
  `service_api2` int(11) NOT NULL DEFAULT 0,
  `api_service2` int(11) NOT NULL DEFAULT 0,
  `birlestirme` set('0','1') NOT NULL DEFAULT '0',
  `sirali_islem` set('0','1') NOT NULL DEFAULT '0',
  `api_servicetype` enum('1','2') CHARACTER SET utf8 NOT NULL DEFAULT '2',
  `api_detail` text CHARACTER SET utf8 NOT NULL,
  `api_detail2` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `service_line` double NOT NULL,
  `service_type` enum('1','2') CHARACTER SET utf8 NOT NULL DEFAULT '2',
  `service_package` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17') CHARACTER SET utf8 NOT NULL,
  `service_name` text NOT NULL,
  `service_description` text NOT NULL,
  `service_price` double NOT NULL DEFAULT 0,
  `sync_price` int(11) NOT NULL,
  `sync_rate` double NOT NULL,
  `service_min` double NOT NULL,
  `sync_min` int(11) NOT NULL,
  `service_max` double NOT NULL,
  `sync_max` int(11) NOT NULL,
  `service_dripfeed` enum('1','2') CHARACTER SET utf8 NOT NULL DEFAULT '1',
  `service_autotime` double NOT NULL DEFAULT 0,
  `service_autopost` double NOT NULL DEFAULT 0,
  `service_speed` enum('1','2','3','4') CHARACTER SET utf8 NOT NULL,
  `want_username` enum('1','2') CHARACTER SET utf8 NOT NULL DEFAULT '1',
  `service_secret` enum('1','2') CHARACTER SET utf8 NOT NULL DEFAULT '2',
  `price_type` enum('normal','percent','amount') CHARACTER SET utf8 NOT NULL DEFAULT 'normal',
  `price_cal` text CHARACTER SET utf8 NOT NULL,
  `start_count` enum('none','instagram_follower','instagram_photo','') CHARACTER SET utf8 NOT NULL,
  `instagram_private` enum('1','2') CHARACTER SET utf8 NOT NULL,
  `paket_set` set('0','1') NOT NULL DEFAULT '0',
  `paket_kategori` int(11) DEFAULT NULL,
  `name_lang` text NOT NULL,
  `description_lang` text CHARACTER SET utf8 NOT NULL,
  `cancel_type` int(11) NOT NULL DEFAULT 1,
  `refill_type` int(11) NOT NULL DEFAULT 1,
  `refill_time` int(11) NOT NULL,
  `refill_min` int(11) NOT NULL DEFAULT 24,
  `rep_link` set('1','2') NOT NULL DEFAULT '1',
  `sync_lastcheck` varchar(225) DEFAULT NULL,
  `provider_lastcheck` varchar(225) DEFAULT NULL,
  `sync_kar_oran` enum('1','0') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `service_api`
--

CREATE TABLE `service_api` (
  `id` int(11) NOT NULL,
  `api_name` varchar(225) NOT NULL,
  `api_url` text NOT NULL,
  `api_key` varchar(225) NOT NULL,
  `api_type` int(11) NOT NULL,
  `api_limit` double NOT NULL DEFAULT 0,
  `api_alert` enum('1','2') NOT NULL DEFAULT '2' COMMENT '2 -> Gönder, 1 -> Gönderildi',
  `api_json` text DEFAULT NULL,
  `update_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `service_report`
--

CREATE TABLE `service_report` (
  `id` int(11) UNSIGNED NOT NULL,
  `service_id` int(5) NOT NULL,
  `alert` text DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_logo` text DEFAULT NULL,
  `site_name` text DEFAULT NULL,
  `site_title` text NOT NULL,
  `site_description` text NOT NULL,
  `site_keywords` text NOT NULL,
  `site_currency` text NOT NULL,
  `dolar` text NOT NULL DEFAULT '8,64',
  `euro` text NOT NULL DEFAULT '10,14',
  `favicon` text DEFAULT NULL,
  `site_language` varchar(225) NOT NULL DEFAULT 'tr',
  `site_theme` text NOT NULL,
  `site_timezone` int(11) NOT NULL,
  `max_ticket` int(11) NOT NULL DEFAULT 2,
  `skype_area` enum('1','2') NOT NULL DEFAULT '1',
  `name_secret` enum('1','2') NOT NULL DEFAULT '1',
  `recaptcha` enum('1','2') NOT NULL DEFAULT '1',
  `recaptcha_key` text DEFAULT NULL,
  `recaptcha_secret` text DEFAULT NULL,
  `custom_header` text DEFAULT NULL,
  `custom_footer` text DEFAULT NULL,
  `ticket_system` enum('1','2') NOT NULL DEFAULT '2',
  `register_page` enum('1','2') NOT NULL DEFAULT '2',
  `terms_checkbox` int(11) NOT NULL DEFAULT 1,
  `service_speed` enum('1','2') NOT NULL DEFAULT '2',
  `service_list` enum('1','2') NOT NULL DEFAULT '2',
  `dolar_charge` double NOT NULL,
  `euro_charge` double NOT NULL,
  `smtp_user` text NOT NULL,
  `smtp_pass` text NOT NULL,
  `smtp_server` text NOT NULL,
  `smtp_port` varchar(225) NOT NULL,
  `smtp_protocol` enum('0','ssl','tls') NOT NULL,
  `smtp_type` varchar(11) NOT NULL,
  `alert_type` enum('1','2','3') NOT NULL,
  `alert_newmanuelservice` enum('1','2') NOT NULL,
  `alert_newticket` enum('1','2') NOT NULL,
  `alert_apibalance` enum('1','2') NOT NULL,
  `alert_newpayment` enum('1','2') NOT NULL,
  `alert_newbankpayment` enum('1','2') NOT NULL DEFAULT '1',
  `alert_serviceapialert` enum('1','2') NOT NULL,
  `alert_failorder` enum('1','2') NOT NULL,
  `admin_mail` varchar(225) NOT NULL,
  `resetpass_page` enum('1','2') NOT NULL,
  `resetpass_email` enum('1','2') NOT NULL,
  `site_maintenance` enum('1','2') NOT NULL DEFAULT '2',
  `site_frozen` int(11) NOT NULL DEFAULT 1,
  `sms_provider` varchar(225) NOT NULL,
  `sms_title` varchar(225) NOT NULL,
  `sms_user` varchar(225) NOT NULL,
  `sms_pass` varchar(225) NOT NULL,
  `admin_telephone` varchar(225) NOT NULL,
  `resetpass_sms` enum('1','2') NOT NULL,
  `panel_selling` int(11) NOT NULL,
  `panel_price` int(11) NOT NULL,
  `free_balance` int(11) NOT NULL,
  `free_amount` int(11) NOT NULL,
  `referral` enum('1','2') NOT NULL DEFAULT '1',
  `ref_bonus` int(11) NOT NULL,
  `ref_max` int(11) NOT NULL,
  `ref_type` enum('0','1') NOT NULL DEFAULT '0',
  `cache` int(11) NOT NULL,
  `cache_time` int(11) NOT NULL,
  `guard_system_status` int(11) NOT NULL,
  `guard_services_status` int(11) NOT NULL,
  `guard_services_type` int(11) NOT NULL,
  `guard_notify_status` int(11) NOT NULL,
  `guard_notify_type` int(11) NOT NULL,
  `guard_roles_status` int(11) NOT NULL,
  `guard_roles_type` int(11) NOT NULL,
  `guard_apikey_type` int(11) NOT NULL,
  `neworder_terms` int(11) NOT NULL,
  `guard_cron_system` int(11) NOT NULL DEFAULT 1,
  `secret_key` varchar(225) NOT NULL,
  `avarage` int(11) NOT NULL,
  `sms_verify` int(11) NOT NULL DEFAULT 1,
  `mail_verify` int(11) NOT NULL DEFAULT 1,
  `ser_sync` int(11) NOT NULL,
  `auto_refill` varchar(225) DEFAULT NULL,
  `notlar` text NOT NULL,
  `version` text NOT NULL,
  `google_ads_odeme` text DEFAULT NULL,
  `google_ads_all` text DEFAULT NULL,
  `mail_sablon` text DEFAULT NULL,
  `up_limiti` varchar(11) NOT NULL DEFAULT '0',
  `proxy_mode` int(11) NOT NULL,
  `alertclosetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `settings`
--

INSERT INTO `settings` (`id`, `site_logo`, `site_name`, `site_title`, `site_description`, `site_keywords`, `site_currency`, `dolar`, `euro`, `favicon`, `site_language`, `site_theme`, `site_timezone`, `max_ticket`, `skype_area`, `name_secret`, `recaptcha`, `recaptcha_key`, `recaptcha_secret`, `custom_header`, `custom_footer`, `ticket_system`, `register_page`, `terms_checkbox`, `service_speed`, `service_list`, `dolar_charge`, `euro_charge`, `smtp_user`, `smtp_pass`, `smtp_server`, `smtp_port`, `smtp_protocol`, `smtp_type`, `alert_type`, `alert_newmanuelservice`, `alert_newticket`, `alert_apibalance`, `alert_newpayment`, `alert_newbankpayment`, `alert_serviceapialert`, `alert_failorder`, `admin_mail`, `resetpass_page`, `resetpass_email`, `site_maintenance`, `site_frozen`, `sms_provider`, `sms_title`, `sms_user`, `sms_pass`, `admin_telephone`, `resetpass_sms`, `panel_selling`, `panel_price`, `free_balance`, `free_amount`, `referral`, `ref_bonus`, `ref_max`, `ref_type`, `cache`, `cache_time`, `guard_system_status`, `guard_services_status`, `guard_services_type`, `guard_notify_status`, `guard_notify_type`, `guard_roles_status`, `guard_roles_type`, `guard_apikey_type`, `neworder_terms`, `guard_cron_system`, `secret_key`, `avarage`, `sms_verify`, `mail_verify`, `ser_sync`, `auto_refill`, `notlar`, `version`, `google_ads_odeme`, `google_ads_all`, `mail_sablon`, `up_limiti`, `proxy_mode`, `alertclosetime`) VALUES
(1, '1652121909_00f7cf86ade56ea9af1b.png', 'Glycon', '', '', '', 'TRY', '16.4933', '17.6281', '1651947846_eca23e94a444423850e3.png', 'tr', 'bootstrap', 0, 2, '2', '2', '1', '', '', '', '', '2', '2', 1, '1', '2', 1, 1, 'noreply@mail.com', 'mailpassword', 'mail.mail.com', '587', 'ssl', 'smtp', '2', '1', '1', '1', '1', '1', '1', '1', 'mail@mail.com', '2', '2', '2', 1, 'netgsm', '', '', '', '', '1', 2, 150, 1, 0, '2', 5, 50, '1', 1, 1, 1, 1, 1, 1, 2, 1, 1, 2, 1, 1, '', 2, 1, 1, 1, '2', 'Özel Notlar.', '10.2', '', '', '<p>&nbsp;{mail_icerik_cek}</p>', '10', 0, '2022-08-29 00:06:14');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `task_type` varchar(225) DEFAULT NULL,
  `task_status` varchar(225) DEFAULT 'pending',
  `task_date` datetime DEFAULT NULL,
  `refill_orderid` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `theme_name` text NOT NULL,
  `theme_dirname` text NOT NULL,
  `theme_extras` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `themes`
--

INSERT INTO `themes` (`id`, `theme_name`, `theme_dirname`, `theme_extras`) VALUES
(1, 'Bootstrap', 'bootstrap', '{\"stylesheets\":[\"assets/css/panel/bootstrap/bootstrap.css\",\"assets/css/panel/bootstrap/style.css\",\"assets/js\\/datepicker\\/css\\/bootstrap-datepicker3.min.css\",\"https:\\/\\/cdn.mypanel.link\\/css\\/font-awesome\\/css\\/all.min.css\"],\"scripts\":[\"https:\\/\\/cdnjs.cloudflare.com\\/ajax\\/libs\\/jquery\\/1.12.4\\/jquery.min.js\",\"assets/js/panel/bootstrap/script.js\",\"assets/js/main.js\",\"assets/js/panel/bootstrap/bootstrap.js\",\"assets/js\\/datepicker\\/js\\/bootstrap-datepicker.min.js\",\"assets/js\\/datepicker\\/locales\\/bootstrap-datepicker.tr.min.js\"]}'),
(2, 'Platinum', 'platinum', '{\"stylesheets\":[\"assets/css/panel/platinum/bootstrap.css\",\"assets/css/panel/platinum/style.css\",\"assets/js\\/datepicker\\/css\\/bootstrap-datepicker3.min.css\",\"https:\\/\\/cdn.mypanel.link\\/css\\/font-awesome\\/css\\/all.min.css\"],\"scripts\":[\"https:\\/\\/cdnjs.cloudflare.com\\/ajax\\/libs\\/jquery\\/1.12.4\\/jquery.min.js\",\"assets/js/panel/platinum/script.js\",\"assets/js/main.js\",\"assets/js/panel/platinum/bootstrap.js\",\"assets/js\\/datepicker\\/js\\/bootstrap-datepicker.min.js\",\"assets/js\\/datepicker\\/locales\\/bootstrap-datepicker.tr.min.js\"]}'),
(3, 'Painite', 'painite', '{\"stylesheets\":[\"assets/css/panel/painite/bootstrap.css\",\"assets/css/panel/painite/style.css\",\"assets/js\\/datepicker\\/css\\/bootstrap-datepicker3.min.css\",\"https:\\/\\/cdn.mypanel.link\\/css\\/font-awesome\\/css\\/all.min.css\"],\"scripts\":[\"https:\\/\\/cdnjs.cloudflare.com\\/ajax\\/libs\\/jquery\\/1.12.4\\/jquery.min.js\",\"assets/js/panel/painite/script.js\",\"assets/js/main.js\",\"assets/js/panel/painite/bootstrap.js\",\"assets/js\\/datepicker\\/js\\/bootstrap-datepicker.min.js\",\"assets/js\\/datepicker\\/locales\\/bootstrap-datepicker.tr.min.js\"]}'),
(4, 'Diamond', 'diamond', '{\"stylesheets\":[\"assets/css/panel/diamond/bootstrap.css\",\"assets/css/panel/diamond/style.css\",\"assets/js\\/datepicker\\/css\\/bootstrap-datepicker3.min.css\",\"https:\\/\\/cdn.mypanel.link\\/css\\/font-awesome\\/css\\/all.min.css\"],\"scripts\":[\"https:\\/\\/cdnjs.cloudflare.com\\/ajax\\/libs\\/jquery\\/1.12.4\\/jquery.min.js\",\"assets/js/panel/diamond/script.js\",\"assets/js/main.js\",\"assets/js/panel/diamond/bootstrap.js\",\"assets/js\\/datepicker\\/js\\/bootstrap-datepicker.min.js\",\"assets/js\\/datepicker\\/locales\\/bootstrap-datepicker.tr.min.js\"]}'),
(5, 'Antimatter', 'antimatter', '{\"stylesheets\":[\"assets/css/panel/antimatter/bootstrap.css\",\"assets/css/panel/antimatter/style.css\",\"js\\/datepicker\\/css\\/bootstrap-datepicker3.min.css\",\"https:\\/\\/cdn.mypanel.link\\/css\\/font-awesome\\/css\\/all.min.css\"],\"scripts\":[\"https:\\/\\/cdnjs.cloudflare.com\\/ajax\\/libs\\/jquery\\/1.12.4\\/jquery.min.js\",\"assets/js/panel/antimatter/script.js\",\"assets/js/main.js\",\"assets/js/panel/antimatter/bootstrap.js\",\"assets/js\\/datepicker\\/js\\/bootstrap-datepicker.min.js\",\"assets/js\\/datepicker\\/locales\\/bootstrap-datepicker.tr.min.js\"]}'),
(6, 'Aqua', 'aqua', '{\"stylesheets\":[\"assets/css/panel/aqua/bootstrap.css\",\"assets/css/panel/aqua/style.css\",\"assets/js\\/datepicker\\/css\\/bootstrap-datepicker3.min.css\",\"https:\\/\\/cdn.mypanel.link\\/css\\/font-awesome\\/css\\/all.min.css\"],\"scripts\":[\"https:\\/\\/cdnjs.cloudflare.com\\/ajax\\/libs\\/jquery\\/1.12.4\\/jquery.min.js\",\"assets/js/panel/aqua/script.js\",\"assets/js/main.js\",\"assets/js/panel/aqua/bootstrap.js\",\"assets/js\\/datepicker\\/js\\/bootstrap-datepicker.min.js\",\"assets/js\\/datepicker\\/locales\\/bootstrap-datepicker.tr.min.js\"]}');
-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `subject` varchar(225) NOT NULL,
  `time` datetime NOT NULL,
  `lastupdate_time` datetime NOT NULL,
  `client_new` enum('1','2') NOT NULL DEFAULT '2',
  `status` enum('pending','answered','closed') NOT NULL DEFAULT 'pending',
  `support_new` enum('1','2') NOT NULL DEFAULT '1',
  `canmessage` enum('1','2') NOT NULL DEFAULT '2',
  `panel_id` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ticket_ready`
--

CREATE TABLE `ticket_ready` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `title` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `ticket_ready`
--

INSERT INTO `ticket_ready` (`id`, `content`, `title`, `created_at`) VALUES
(3, '<p>Merhaba,</p>\n<p>&nbsp;</p>\n<p>Keyifli Alışverişler,</p>\n<p><strong>Glycon Destek Ekibi</strong></p>', 'İmza', '2021-10-30 04:58:02');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ticket_reply`
--

CREATE TABLE `ticket_reply` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `support_team` varchar(225) NOT NULL DEFAULT '',
  `time` datetime NOT NULL,
  `support` enum('1','2') NOT NULL DEFAULT '1',
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ticket_subjects`
--

CREATE TABLE `ticket_subjects` (
  `subject_id` int(11) NOT NULL,
  `subject` varchar(225) NOT NULL,
  `content` text DEFAULT NULL,
  `auto_reply` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Tablo döküm verisi `ticket_subjects`
--

INSERT INTO `ticket_subjects` (`subject_id`, `subject`, `content`, `auto_reply`) VALUES
(14, 'Sipariş Sorunları', '', '0'),
(15, 'Ödeme Sorunları', '', '0'),
(16, 'Bayilik Hakkında', '', '0'),
(17, 'Şikayet & Öneri', '', '0'),
(18, 'Diğer', '', '0');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `verify_log`
--

CREATE TABLE `verify_log` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `token` varchar(225) NOT NULL,
  `type` int(11) NOT NULL,
  `verify` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Tablo için indeksler `child_panels`
--
ALTER TABLE `child_panels`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cift_servis`
--
ALTER TABLE `cift_servis`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `clients_category`
--
ALTER TABLE `clients_category`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `clients_price`
--
ALTER TABLE `clients_price`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `clients_service`
--
ALTER TABLE `clients_service`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `client_favorite`
--
ALTER TABLE `client_favorite`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `client_report`
--
ALTER TABLE `client_report`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `cron_status`
--
ALTER TABLE `cron_status`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `guard_log`
--
ALTER TABLE `guard_log`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `integrations`
--
ALTER TABLE `integrations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `api_orderid` (`api_orderid`),
  ADD KEY `order_api` (`order_api`),
  ADD KEY `api_serviceid` (`api_serviceid`);

--
-- Tablo için indeksler `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`),
  ADD UNIQUE KEY `page_id` (`page_id`);

--
-- Tablo için indeksler `paket_kategori`
--
ALTER TABLE `paket_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `payment_privatecode` (`payment_privatecode`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `client_balance` (`client_balance`),
  ADD KEY `payment_amount` (`payment_amount`),
  ADD KEY `payment_method` (`payment_method`),
  ADD KEY `payment_status` (`payment_status`);

--
-- Tablo için indeksler `payments_bonus`
--
ALTER TABLE `payments_bonus`
  ADD PRIMARY KEY (`bonus_id`);

--
-- Tablo için indeksler `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `popup`
--
ALTER TABLE `popup`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `proxy`
--
ALTER TABLE `proxy`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `referral`
--
ALTER TABLE `referral`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `reset_log`
--
ALTER TABLE `reset_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Tablo için indeksler `serviceapi_alert`
--
ALTER TABLE `serviceapi_alert`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Tablo için indeksler `service_api`
--
ALTER TABLE `service_api`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `service_report`
--
ALTER TABLE `service_report`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Tablo için indeksler `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Tablo için indeksler `ticket_ready`
--
ALTER TABLE `ticket_ready`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `ticket_reply`
--
ALTER TABLE `ticket_reply`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `ticket_subjects`
--
ALTER TABLE `ticket_subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Tablo için indeksler `verify_log`
--
ALTER TABLE `verify_log`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `child_panels`
--
ALTER TABLE `child_panels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `cift_servis`
--
ALTER TABLE `cift_servis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `clients_category`
--
ALTER TABLE `clients_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `clients_price`
--
ALTER TABLE `clients_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `clients_service`
--
ALTER TABLE `clients_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `client_favorite`
--
ALTER TABLE `client_favorite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `client_report`
--
ALTER TABLE `client_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `cron_status`
--
ALTER TABLE `cron_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Tablo için AUTO_INCREMENT değeri `guard_log`
--
ALTER TABLE `guard_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `integrations`
--
ALTER TABLE `integrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `paket_kategori`
--
ALTER TABLE `paket_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `payments_bonus`
--
ALTER TABLE `payments_bonus`
  MODIFY `bonus_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `popup`
--
ALTER TABLE `popup`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `proxy`
--
ALTER TABLE `proxy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `referral`
--
ALTER TABLE `referral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `reset_log`
--
ALTER TABLE `reset_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `serviceapi_alert`
--
ALTER TABLE `serviceapi_alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `service_api`
--
ALTER TABLE `service_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `service_report`
--
ALTER TABLE `service_report`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `ticket_ready`
--
ALTER TABLE `ticket_ready`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `ticket_reply`
--
ALTER TABLE `ticket_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `ticket_subjects`
--
ALTER TABLE `ticket_subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `verify_log`
--
ALTER TABLE `verify_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
