<?php
namespace Hostinger\EasyOnboarding\Rest;

/**
 * Avoid possibility to get file accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class TutorialRoutes {
    public function get_tutorials( \WP_REST_Request $request ): \WP_REST_Response {
        $parameters = $request->get_params();

        $locale = sanitize_text_field( $parameters['locale'] );

        $user_locale = !empty( $locale ) ? substr( $locale, 0, 2) : 'en';

        $tutorials = array(
            'en' => array(
                array(
                    'id'       => 'F_53_baJe6Q',
                    'title'    => __( 'How to Make a Website (2024): Simple, Quick, & Easy Tutorial', 'hostinger-easy-onboarding' ),
                    'duration' => '17:47',
                ),
                array(
                    'id'       => 'SU_DOsu9Llk',
                    'title'    => __( 'How to EASILY Manage Google Tools with Google Site Kit - Beginners Guide 2024', 'hostinger-easy-onboarding' ),
                    'duration' => '12:28',
                ),
                array(
                    'id'       => 'YK-XO7iLyGQ',
                    'title'    => __( 'How to Import Images Into WordPress Website', 'hostinger-easy-onboarding' ),
                    'duration' => '1:44',
                ),
                array(
                    'id'       => 'WHXtmEppbn8',
                    'title'    => __( 'How to Edit the Footer in WordPress', 'hostinger-easy-onboarding' ),
                    'duration' => '6:17',
                ),
            ),
            'pt' => array(
                array(
                    'id'       => 'Ck15HW4koWE',
                    'title'    => __( 'Como Alterar a sua Logo no WordPress (Rápido e Prático)', 'hostinger-easy-onboarding' ),
                    'duration' => '4:28',
                ),
                array(
                    'id'       => 'OJH713cx-u4',
                    'title'    => __( 'Como Personalizar um Tema do WordPress', 'hostinger-easy-onboarding' ),
                    'duration' => '13:42',
                ),
                array(
                    'id'       => 'X_04utuq750',
                    'title'    => __( 'Como Editar o Menu dos Temas do WordPress', 'hostinger-easy-onboarding' ),
                    'duration' => '4:53',
                ),
                array(
                    'id'       => 'cMKPatPvSKk',
                    'title'    => __( 'Como Criar Categorias no WordPress', 'hostinger-easy-onboarding' ),
                    'duration' => '6:04',
                ),
            ),
            'es' => array(
                array(
                    'id'       => 'FKp0dvhEN8o',
                    'title'    => __( 'Cómo Personalizar WordPress (2023)', 'hostinger-easy-onboarding' ),
                    'duration' => '9:02',
                ),
                array(
                    'id'       => '1tvYSsRSgNc',
                    'title'    => __( 'Cómo Crear una Galería de Fotos en WordPress | Fácil y Gratis', 'hostinger-easy-onboarding' ),
                    'duration' => '5:48',
                ),
                array(
                    'id'       => 'A-yuq3g1KVs',
                    'title'    => __( 'Como Instalar Plugins y Temas en WordPress', 'hostinger-easy-onboarding' ),
                    'duration' => '4:54',
                ),
                array(
                    'id'       => '_8Z0C6Os1CQ',
                    'title'    => __( 'Cómo Crear un Menú en WordPress (en Menos de 5 minutos)', 'hostinger-easy-onboarding' ),
                    'duration' => '4:52',
                ),
            ),
            'fr' => array(
                array(
                    'id'       => 'X7ZA9pteqqQ',
                    'title'    => __( 'TUTO WORDPRESS (Débutant) : Créer un site WordPress pour les Nuls)', 'hostinger-easy-onboarding' ),
                    'duration' => '12:56',
                ),
                array(
                    'id'       => 'JIHy3Y6ek_s',
                    'title'    => __( 'Tuto WordPress Débutant (Hostinger hPanel) - Créer un Site par IA', 'hostinger-easy-onboarding' ),
                    'duration' => '7:21',
                ),
                array(
                    'id'       => 'Te3fM7VuQKg',
                    'title'    => __( 'Installer un Thème WordPress (2023) | Rapide et Facile', 'hostinger-easy-onboarding' ),
                    'duration' => '2:58',
                ),
                array(
                    'id'       => '2rPq1CiogDk',
                    'title'    => __( 'Google Analytics sur WordPress FACILEMENT avec Google Site Kit : Guide Complet (2023)', 'hostinger-easy-onboarding' ),
                    'duration' => '7:19',
                ),
            ),
            'hi' => array(
                array(
                    'id'       => '4wGytQfbmm4',
                    'title'    => __( 'How to Build a Website FAST Using AI in Just 10 Minutes', 'hostinger-easy-onboarding' ),
                    'duration' => '8:32',
                ),
                array(
                    'id'       => 'AT73ExGMuVc',
                    'title'    => __( 'How to Edit Footer in WordPress in Hindi | Hostinger India', 'hostinger-easy-onboarding' ),
                    'duration' => '3:48',
                ),
                array(
                    'id'       => 'OIGsBGIaZqM',
                    'title'    => __( 'How to Create a Menu in WordPress in Hindi | Hostinger India', 'hostinger-easy-onboarding' ),
                    'duration' => '2:38',
                ),
                array(
                    'id'       => 'WFBoHv0xJ60',
                    'title'    => __( 'How to Install WordPress Themes | Hostinger India', 'hostinger-easy-onboarding' ),
                    'duration' => '2:52',
                ),
            ),
        );

        if ( empty( $tutorials[$user_locale] ) ) {
            $user_locale = 'en';
        }

        $data = array(
            'data' => array(
                'tutorials'  => $tutorials[$user_locale],
            )
        );

        $response = new \WP_REST_Response( $data );

        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );

        $response->set_status( \WP_Http::OK );

        return $response;
    }

}