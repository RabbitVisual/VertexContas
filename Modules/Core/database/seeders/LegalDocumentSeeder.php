<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\LegalDocument;

class LegalDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'slug' => 'termos-de-uso',
                'title' => 'Termos de Uso',
                'content' => '<h1>Termos de Uso - Vertex Contas</h1><p>Estes Termos de Uso regem o uso do serviço Vertex Contas, operado pela <strong>Vertex Solutions LTDA</strong>, pessoa jurídica de direito privado, inscrita sob CNPJ, com sede no Brasil.</p><p>Ao utilizar nossos serviços, o usuário declara ter lido, compreendido e concordado integralmente com estes termos, em conformidade com a legislação brasileira vigente.</p>',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_acceptance' => true,
            ],
            [
                'slug' => 'privacidade',
                'title' => 'Política de Privacidade (LGPD)',
                'content' => '<h1>Política de Privacidade - LGPD</h1><p>A <strong>Vertex Solutions LTDA</strong> está comprometida com a proteção dos dados pessoais de seus usuários, em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018).</p><p>Esta política descreve como coletamos, armazenamos, utilizamos e protegemos suas informações pessoais, garantindo transparência e o exercício dos direitos previstos na LGPD.</p>',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_acceptance' => true,
            ],
            [
                'slug' => 'termos-assinatura',
                'title' => 'Termos de Assinatura',
                'content' => '<h1>Termos de Assinatura - Vertex Contas</h1><p>Os presentes Termos de Assinatura celebrados entre o usuário e a <strong>Vertex Solutions LTDA</strong> estabelecem as condições da prestação do serviço de controle financeiro mediante assinatura.</p><p>O contrato é celebrado em conformidade com o Código de Defesa do Consumidor e demais normas aplicáveis no território nacional.</p>',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_acceptance' => false,
            ],
            [
                'slug' => 'politica-cookies',
                'title' => 'Política de Cookies',
                'content' => '<h1>Política de Cookies - Vertex Contas</h1><p>A <strong>Vertex Solutions LTDA</strong> utiliza cookies e tecnologias similares para melhorar a experiência do usuário em nossa plataforma Vertex Contas.</p><p>Esta política descreve os tipos de cookies utilizados, suas finalidades e como o usuário pode gerenciar suas preferências, em conformidade com a LGPD e as melhores práticas de privacidade.</p>',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_acceptance' => false,
            ],
        ];

        foreach ($documents as $document) {
            LegalDocument::updateOrCreate(
                ['slug' => $document['slug']],
                $document
            );
        }
    }
}
