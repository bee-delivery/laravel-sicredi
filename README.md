# Sicredi Pix Package

Este pacote permite integração com o Sicredi Pix, suportando múltiplas contas (credenciais) por alias.

## Instalação

Adicione ao seu projeto via Composer:

```bash
composer require beedelivery/sicredi-pix
```

## Configuração

Publique o arquivo de configuração:

```bash
php artisan vendor:publish --provider="Beedelivery\Sicredi\SicrediServiceProvider"
```

### Configuração de múltiplas contas

No arquivo `config/sicredi-pix.php`, adicione suas contas no array `accounts`:

```php
'accounts' => [
    'conta1' => [
        'base_url'         => env('SICREDI_PIX_URL_CONTA1', ''),
        'client_id'        => env('SICREDI_PIX_CLIENT_ID_CONTA1', ''),
        'client_secret'    => env('SICREDI_PIX_CLIENT_SECRET_CONTA1', ''),
        'certificate_path' => env('SICREDI_PIX_CERTIFICATE_CONTA1', ''),
        'cert_key_path'    => env('SICREDI_PIX_CERT_KEY_CONTA1', ''),
        'cert_key_pass'    => env('SICREDI_PIX_CERT_PASS_CONTA1', ''),
        'cooperativa'      => env('SICREDI_PIX_COOPERATIVA_CONTA1', ''),
        'conta'            => env('SICREDI_PIX_CONTA_CONTA1', ''),
        'documento'        => env('SICREDI_PIX_DOCUMENTO_CONTA1', ''),
    ],
    'conta2' => [
        // ...outra conta...
    ],
],
```

Você pode manter a configuração padrão para retrocompatibilidade:

```php
'base_url'         => env('SICREDI_PIX_URL', ''),
'client_id'        => env('SICREDI_PIX_CLIENT_ID', ''),
// ...
```

## Uso

### Instanciando para uma conta específica (por alias)

```php
use Beedelivery\Sicredi\Pix;

$pixConta1 = new Pix('conta1');
$pixConta2 = new Pix('conta2');
```

### Instanciando com a configuração padrão (sem alias)

```php
$pix = new Pix();
```

### Criando um pagamento Pix

```php
$params = [
    'valor' => 100.00,
    'chave' => 'chavepix@exemplo.com',
    // outros parâmetros obrigatórios...
];

$resultado = $pixConta1->createPayment($params);
```

### Consultando um pagamento Pix

```php
$idTransacao = '123456789';
$resultado = $pixConta1->getPayment($idTransacao);
```

### Cancelando um pagamento Pix

```php
$idTransacao = '123456789';
$resultado = $pixConta1->cancelPayment($idTransacao);
```

## Observações
- Sempre utilize o alias correto para cada operação, conforme configurado no arquivo de configuração.
- O token de acesso é gerenciado automaticamente para cada conta/alias.

---

Dúvidas ou sugestões? Abra uma issue ou contribua com o projeto!