<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Service,Equipment,Reference,Product,Setting};
use Illuminate\Support\Str;
class ContentSeeder extends Seeder {
    public function run(): void {
        $services=[
            ['name'=>'Location d’engins TP','short_description'=>'Mise à disposition d’équipements de chantier adaptés.'],
            ['name'=>'Transport & logistique','short_description'=>'Transport de matériels et solutions logistiques pour les projets.'],
            ['name'=>'Terrassement & travaux','short_description'=>'Solutions de terrassement et travaux de chantier.'],
            ['name'=>'Levage & manutention','short_description'=>'Moyens de levage et de manutention pour les opérations professionnelles.'],
            ['name'=>'Commerce & sécurité','short_description'=>'Équipements de sécurité disponibles en gros et au détail.'],
        ];
        foreach($services as $s) Service::updateOrCreate(['slug'=>Str::slug($s['name'])],$s+['slug'=>Str::slug($s['name'])]);
        foreach([
            ['name'=>'Pelle hydraulique','category'=>'Terrassement'],
            ['name'=>'Bulldozer','category'=>'Terrassement'],
            ['name'=>'Tractopelle','category'=>'Terrassement'],
            ['name'=>'Niveleuse','category'=>'Nivellement'],
            ['name'=>'Compacteur monocylindre','category'=>'Compactage'],
            ['name'=>'Chargeuse','category'=>'Chargement'],
            ['name'=>'Camion','category'=>'Transport'],
            ['name'=>'Porte-chars','category'=>'Transport'],
            ['name'=>'Manitou','category'=>'Levage'],
            ['name'=>'Grue mobile','category'=>'Levage'],
            ['name'=>'Nacelle élévatrice','category'=>'Levage'],
        ] as $e) Equipment::updateOrCreate(['slug'=>Str::slug($e['name'])],$e+['slug'=>Str::slug($e['name'])]);
        foreach(['VINCI Energies','Bouygues','RMT','Arabian Construction','Hitech-BTP','SIMAU','GDIZ','AMINE','ACC'] as $name) Reference::firstOrCreate(['name'=>$name]);
        foreach(['Longe anti-chute','Longe de maintien','Chaussures de sécurité','Harnais / ceinture de montage'] as $name) Product::updateOrCreate(['slug'=>Str::slug($name)],['name'=>$name,'slug'=>Str::slug($name),'category'=>'Sécurité']);
        Setting::updateOrCreate(['key'=>'company'],['value'=>[
            'name'=>'SLTC INTER SARL',
            'tagline'=>'Finesse & promptitude !',
            'slogan'=>'Built to Deliver',
            'address'=>'Kouhounou – Cotonou – Bénin',
            'email'=>'contact@sltc-inter.bj',
            'phone_1'=>'01 96 74 89 41',
            'phone_2'=>'01 97 52 58 07',
            'whatsapp'=>'2290196748941',
            'website'=>'https://sltc-inter.bj',
            'founded'=>2015,
            'mission'=>"Mettre à votre disposition les moyens humains, matériels et logistiques nécessaires à la réussite de vos projets.",
            'vision'=>"Être le partenaire de référence pour les solutions d'équipements, transport, logistique et opérations de chantier en Afrique de l'Ouest.",
            'engagement'=>"Nous nous engageons à fournir des prestations de qualité, dans le respect des délais et des normes de sécurité, tout en accompagnant nos clients à chaque étape de leur projet.",
            'means'=>"Notre parc se compose de plus de 45 engins et véhicules spécialisés, d'équipes formées et expérimentées, et d'une organisation logistique capable d'intervenir sur l'ensemble du territoire béninois et dans les pays voisins.",
            'values'=>[
                ['title'=>'Fiabilité','description'=>'Des prestations exécutées avec rigueur.'],
                ['title'=>'Réactivité','description'=>'Une réponse rapide aux besoins des clients.'],
                ['title'=>'Moyens','description'=>'Une flotte et des ressources adaptées.'],
                ['title'=>'Engagement','description'=>"Un accompagnement du projet jusqu'à son exécution."],
            ],
            'history'=>[
                ['year'=>'2015','event'=>'Création de SLTC INTER SARL à Cotonou'],
                ['year'=>'2017','event'=>'Premiers projets de transport de poteaux'],
                ['year'=>'2019','event'=>"Développement de la flotte d'engins"],
                ['year'=>'2021','event'=>'Expansion des activités logistiques'],
                ['year'=>'2023','event'=>'Projets majeurs avec VINCI Energies et Bouygues'],
                ['year'=>'2024','event'=>"Aujourd'hui : partenaire de confiance pour les grands comptes"],
            ],
            'stats'=>[
                'poles'=>5,
                'projects'=>150,
                'clients'=>80,
                'equipment'=>45,
                'polesTransported'=>33000,
                'yearsExperience'=>9,
            ],
        ]]);
    }
}