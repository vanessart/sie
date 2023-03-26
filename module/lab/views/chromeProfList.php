<?php
if (!defined('ABSPATH'))
    exit;

$nome = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'userEmail', FILTER_SANITIZE_STRING);
$emailFixo = explode('@', $email)[1];
$prefixo = [
    'os',
    'call',
    'contato',
    'coord',
    'coord1',
    'coord2',
    'coord3',
    'coord4',
    'coord5',
    'dae',
    'dap',
    'dir',
    'edu',
    'ef',
    'olhonaescola',
    'orient',
    'orient1',
    'orient2',
    'orient3',
    'sec',
    'sec1',
    'sec2',
    'sec3',
    'sec4',
    'sec5',
    'ue',
    'bin',
];
$emailNo = [
    'ue.osvaldo@educbarueri.sp.gov.br',
    'ue.sidneysantucci@educbarueri.sp.gov.br',
    'ue.meduneckas.maternal@educbarueri.sp.gov.br',
    'ue.osmarinho.maternal@educbarueri.sp.gov.br',
    'ue.eneias@educbarueri.sp.gov.br',
    'ue.suzete@educbarueri.sp.gov.br',
    'ue.mariaandrelina@educbarueri.sp.gov.br',
    'ue.sandroluiz@educbarueri.sp.gov.br',
    'ue.josedomingos@educbarueri.sp.gov.br',
    'ue.lucineia@educbarueri.sp.gov.br',
    'ue.joseleandro@educbarueri.sp.gov.br',
    'ue.juliogomescamisao@educbarueri.sp.gov.br',
    'ue.eizaburonomura@educbarueri.sp.gov.br',
    'ue.gilbertoflorencio@educbarueri.sp.gov.br',
    'ue.meduneckas.emei@educbarueri.sp.gov.br',
    'ue.mariaelisachaluppe@educbarueri.sp.gov.br',
    'ue.joaofernandes@educbarueri.sp.gov.br',
    'ue.alfredo@educbarueri.sp.gov.br',
    'ue.dalva@educbarueri.sp.gov.br',
    'ue.joaoevangelista@educbarueri.sp.gov.br',
    'ue.aparecidaakyama@educbarueri.sp.gov.br',
    'ue.ritadecassia@educbarueri.sp.gov.br',
    'ue.thomazvictoria@educbarueri.sp.gov.br',
    'ue.leonardoaugusto@educbarueri.sp.gov.br',
    'ue.padreelidio@educbarueri.sp.gov.br',
    'ue.joaobatistapazinato@educbarueri.sp.gov.br',
    'ue.deiro@educbarueri.sp.gov.br',
    'ue.mariadolores@educbarueri.sp.gov.br',
    'ue.ivani@educbarueri.sp.gov.br',
    'ue.robertogriti@educbarueri.sp.gov.br',
    'ue.brunotolaini@educbarueri.sp.gov.br',
    'ue.matildeabreu@educbarueri.sp.gov.br',
    'ue.renatorosa@educbarueri.sp.gov.br',
    'ue.takechitakau@educbarueri.sp.gov.br',
    'ue.annairene@educbarueri.sp.gov.br',
    'ue.armandocavazza@educbarueri.sp.gov.br',
    'ue.mariobezerra@educbarueri.sp.gov.br',
    'dap.revisao@educbarueri.sp.gov.br',
    'ue.osmarinho.emei@educbarueri.sp.gov.br',
    'juridico@educbarueri.sp.gov.br',
    'ue.padrerenaldo@educbarueri.sp.gov.br',
    'ue.ricardopeagno@educbarueri.sp.gov.br',
    'coord2.beneditoadherbal@educbarueri.sp.gov.br',
    'ue.joaquimsoares@educbarueri.sp.gov.br',
    'coord1.juliogomescamisao@educbarueri.sp.gov.br',
    'ue.beneditovenancio@educbarueri.sp.gov.br',
    'coord1.aparecidaakyama@educbarueri.sp.gov.br',
    'coord2.mariadolores@educbarueri.sp.gov.br',
    'coord1.lenio@educbarueri.sp.gov.br',
    'coord1.padreelidio@educbarueri.sp.gov.br',
    'coord1.estevan@educbarueri.sp.gov.br',
    'coord2.juliogomescamisao@educbarueri.sp.gov.br',
    'dir.thomazvictoria@educbarueri.sp.gov.br',
    'coord1.gilbertoflorencio@educbarueri.sp.gov.br',
    'ue.rogeliolopez@educbarueri.sp.gov.br',
    'coord1.onofra@educbarueri.sp.gov.br',
    'coord2.alcino@educbarueri.sp.gov.br',
    'coord2.dalva@educbarueri.sp.gov.br',
    'coord2.mariademenezes@educbarueri.sp.gov.br',
    'coord1.mariojoaquim@educbarueri.sp.gov.br',
    'coord1.elizabeth@educbarueri.sp.gov.br',
    'coord1.joaodealmeida@educbarueri.sp.gov.br',
    'coord1.joseemidio@educbarueri.sp.gov.br',
    'coord1.agenor@educbarueri.sp.gov.br',
    'coord2.estevan@educbarueri.sp.gov.br',
    'coord1.ezio@educbarueri.sp.gov.br',
    'coord2.dorival@educbarueri.sp.gov.br',
    'ue.mariajosedebarros@educbarueri.sp.gov.br',
    'coord2.aristides@educbarueri.sp.gov.br',
    'coord2.renatorosa@educbarueri.sp.gov.br',
    'coord2.ezio@educbarueri.sp.gov.br',
    'coord1.mariameduneckas.emm@educbarueri.sp.gov.br',
    'dap.matematica@educbarueri.sp.gov.br',
    'coord1.nestor@educbarueri.sp.gov.br',
    'dir.primeirainfancia@educbarueri.sp.gov.br',
    'orient2.joseemidio@educbarueri.sp.gov.br',
    'coord1.mariamedunekas.emef@educbarueri.sp.gov.br',
    'coord1.brunotolaini@educbarueri.sp.gov.br',
    'dir.estevan@educbarueri.sp.gov.br',
    'coord2.deiro@educbarueri.sp.gov.br',
    'dap.pedagogico@educbarueri.sp.gov.br',
    'ue.joseemidio@educbarueri.sp.gov.br',
    'ue.osmarinho.emef@educbarueri.sp.gov.br',
    'ue.deciotrujillo@educbarueri.sp.gov.br',
    'ue.joaotiburcio@educbarueri.sp.gov.br',
    'ue.marlene@educbarueri.sp.gov.br',
    'ue.valdineia@educbarueri.sp.gov.br',
    'ue.dorival@educbarueri.sp.gov.br',
    'ue.estevam@educbarueri.sp.gov.br',
    'ue.meduneckas.emef@educbarueri.sp.gov.br',
    'ue.franciscozacarioto@educbarueri.sp.gov.br',
    'ue.robertoluis@educbarueri.sp.gov.br',
    'ue.taromizutori@educbarueri.sp.gov.br',
    'ue.beneditoadherbal@educbarueri.sp.gov.br',
    'ue.fioravantebarletta@educbarueri.sp.gov.br',
    'ue.elvira@educbarueri.sp.gov.br',
    'ue.margarida@educbarueri.sp.gov.br',
    'ue.joaocarvalho@educbarueri.sp.gov.br',
    'ue.amador@educbarueri.sp.gov.br',
    'ue.elizabeth@educbarueri.sp.gov.br',
    'ue.padreluiz@educbarueri.sp.gov.br',
    'ue.nestor@educbarueri.sp.gov.br',
    'ue.naly@educbarueri.sp.gov.br',
    'ue.agenor@educbarueri.sp.gov.br',
    'ue.alcino@educbarueri.sp.gov.br',
    'ue.ritadejesus@educbarueri.sp.gov.br',
    'ue.ezio@educbarueri.sp.gov.br',
    'ue.raposo@educbarueri.sp.gov.br',
    'ue.elainecalsolari@educbarueri.sp.gov.br',
    'ue.elianecastanon@educbarueri.sp.gov.br',
    'ue.aristides@educbarueri.sp.gov.br',
    'ue.alexandrino@educbarueri.sp.gov.br',
    'ue.yojirotakaoka@educbarueri.sp.gov.br',
    'ue.lenio@educbarueri.sp.gov.br',
    'ue.egidio@educbarueri.sp.gov.br',
    'ue.marisaodaine@educbarueri.sp.gov.br',
    'ue.roquesoares@educbarueri.sp.gov.br',
    'ue.joaodealmeida@educbarueri.sp.gov.br',
    'ue.mariarosa@educbarueri.sp.gov.br',
    'ue.mariojoaquim@educbarueri.sp.gov.br',
    'ue.eminoldo@educbarueri.sp.gov.br',
    'ue.josevital@educbarueri.sp.gov.br',
    'ue.jorgeaugusto@educbarueri.sp.gov.br',
    'ue.levy@educbarueri.sp.gov.br',
    'ue.mariademenezes@educbarueri.sp.gov.br',
    'ue.onofra@educbarueri.sp.gov.br',
    'coord3.margarida@educbarueri.sp.gov.br',
    'coord1.armandocavazza@educbarueri.sp.gov.br',
    'coord2.mariojoaquim@educbarueri.sp.gov.br',
    'dme.manutencao@educbarueri.sp.gov.br',
    'coord1.fioravantebarletta@educbarueri.sp.gov.br',
    'ti@educbarueri.sp.gov.br',
    'coord1.deciotrujillo@educbarueri.sp.gov.br',
    'coord1.marlene@educbarueri.sp.gov.br',
    'dir.sandroluiz@educbarueri.sp.gov.br',
    'coord1.aristides@educbarueri.sp.gov.br',
    'dee2@educbarueri.sp.gov.br',
    'coord1.mariadolores@educbarueri.sp.gov.br',
    'coord2.padreluiz@educbarueri.sp.gov.br',
    'coord1.jorgeaugusto@educbarueri.sp.gov.br',
    'coord1.raposo@educbarueri.sp.gov.br',
    'educ.primeirainfancia@educbarueri.sp.gov.br',
    'dap.infantil@educbarueri.sp.gov.br',
    'coord1.joseleandro@educbarueri.sp.gov.br',
    'orient2.levy@educbarueri.sp.gov.br',
    'orient2.estevan@educbarueri.sp.gov.br',
    'coord1.mariarosa@educbarueri.sp.gov.br',
    'coord1.alcino@educbarueri.sp.gov.br',
    'coord3.dorival@educbarueri.sp.gov.br',
    'coord2.joseemidio@educbarueri.sp.gov.br',
    'coord1.robertoluis@educbarueri.sp.gov.br',
    'coord2.levy@educbarueri.sp.gov.br',
    'orient2.dorival@educbarueri.sp.gov.br',
    'coord1.alfredo@educbarueri.sp.gov.br',
    'orient2.renatorosa@educbarueri.sp.gov.br',
    'coord2.joaocarvalho@educbarueri.sp.gov.br',
    'coord2.mariamedunekas.emef@educbarueri.sp.gov.br',
    'dir.joaquimsoares@educbarueri.sp.gov.br',
    'dap.ciencias@educbarueri.sp.gov.br',
    'orient1.onofra@educbarueri.sp.gov.br',
    'coord3.beneditoadherbal@educbarueri.sp.gov.br',
    'dap.arte@educbarueri.sp.gov.br',
    'dir.dorival@educbarueri.sp.gov.br',
    'coord2.deciotrujillo@educbarueri.sp.gov.br',
    'orient1.joseemidio@educbarueri.sp.gov.br',
    'coord2.joseleandro@educbarueri.sp.gov.br',
    'coord2.nestor@educbarueri.sp.gov.br',
    'coord2.onofra@educbarueri.sp.gov.br',
    'orient1.dalva@educbarueri.sp.gov.br',
    'coord2.margarida@educbarueri.sp.gov.br',
    'orient1.sidneysantucci@educbarueri.sp.gov.br',
    'coord1.joaotiburcio@educbarueri.sp.gov.br',
    'coord2.raposo@educbarueri.sp.gov.br',
    'dap.geografia@educbarueri.sp.gov.br',
    'orient1.estevan@educbarueri.sp.gov.br',
    'coord1.padreluiz@educbarueri.sp.gov.br',
    'orient1.agenor@educbarueri.sp.gov.br',
    'coord1.leonardoaugusto@educbarueri.sp.gov.br',
    'contato@educbarueri.sp.gov.br',
    'coord1.dalva@educbarueri.sp.gov.br',
    'coord1.annairene@educbarueri.sp.gov.br',
    'orient3.dorival@educbarueri.sp.gov.br',
    'coord2.fioravantebarletta@educbarueri.sp.gov.br',
    'coord2.ritadejesus@educbarueri.sp.gov.br',
    'coord1.joaocarvalho@educbarueri.sp.gov.br',
    'grupoderisco@educbarueri.sp.gov.br',
    'coord1.levy@educbarueri.sp.gov.br',
    'coord1.osvaldo@educbarueri.sp.gov.br',
    'orient2.juliogomescamisao@educbarueri.sp.gov.br',
    'orient1.dorival@educbarueri.sp.gov.br',
    'coord2.sidneysantucci@educbarueri.sp.gov.br',
    'dir.fioravantebarletta@educbarueri.sp.gov.br',
    'coord2.amador@educbarueri.sp.gov.br',
    'coord2.osvaldo@educbarueri.sp.gov.br',
    'coord1.mariaandrelina@educbarueri.sp.gov.br',
    'coord1.osmarinho.emef@educbarueri.sp.gov.br',
    'orient1.marlene@educbarueri.sp.gov.br',
    'coord1.egidio@educbarueri.sp.gov.br',
    'coord1.eminoldo@educbarueri.sp.gov.br',
    'orient1.robertoluis@educbarueri.sp.gov.br',
    'orient1.mariamedunekas.emef@educbarueri.sp.gov.br',
    'coord1.lucineia@educbarueri.sp.gov.br',
    'coord1.ritadejesus@educbarueri.sp.gov.br',
    'dir.renatorosa@educbarueri.sp.gov.br',
    'dir.beneditoadherbal@educbarueri.sp.gov.br',
    'orient1.beneditoadherbal@educbarueri.sp.gov.br',
    'orient1.padreluiz@educbarueri.sp.gov.br',
    'coord1.deiro@educbarueri.sp.gov.br',
    'coord2.elvira@educbarueri.sp.gov.br',
    'dee@educbarueri.sp.gov.br',
    'dir.joseemidio@educbarueri.sp.gov.br',
    'orient1.juliogomescamisao@educbarueri.sp.gov.br',
    'coord1.robertogriti@educbarueri.sp.gov.br',
    'orient1.joaotiburcio@educbarueri.sp.gov.br',
    'coord3.joaotiburcio@educbarueri.sp.gov.br',
    'dir.onofra@educbarueri.sp.gov.br',
    'coord1.eizaburonomura@educbarueri.sp.gov.br',
    'orient2.ritadejesus@educbarueri.sp.gov.br',
    'dir.osvaldo@educbarueri.sp.gov.br',
    'orient1.deiro@educbarueri.sp.gov.br',
    'coord1.ritadecassia@educbarueri.sp.gov.br',
    'dir.osmarinho.emef@educbarueri.sp.gov.br',
    'orient1.ivani@educbarueri.sp.gov.br',
    'orient3.estevan@educbarueri.sp.gov.br',
    'orient1.gilbertoflorencio@educbarueri.sp.gov.br',
    'orient1.elizabeth@educbarueri.sp.gov.br',
    'orient1.osmarinho.emef@educbarueri.sp.gov.br',
    'dap.eja@educbarueri.sp.gov.br',
    'orient1.lenio@educbarueri.sp.gov.br',
    'dap.historia@educbarueri.sp.gov.br',
    'dir.lucineia@educbarueri.sp.gov.br',
    'coord1.roquesoares@educbarueri.sp.gov.br',
    'dir.joaoevangelista@educbarueri.sp.gov.br',
    'coord1.sidneysantucci@educbarueri.sp.gov.br',
    'coord2.suzete@educbarueri.sp.gov.br',
    'orient1.brunotolaini@educbarueri.sp.gov.br',
    'orient1.margarida@educbarueri.sp.gov.br',
    'dap.edfisica@educbarueri.sp.gov.br',
    'dpi.projetos@educbarueri.sp.gov.br',
    'coord3.ezio@educbarueri.sp.gov.br',
    'orient1.mariojoaquim@educbarueri.sp.gov.br',
    'orient2.armandocavazza@educbarueri.sp.gov.br',
    'dir.ivani@educbarueri.sp.gov.br',
    'coord1.yojirotakaoka@educbarueri.sp.gov.br',
    'orient1.joseleandro@educbarueri.sp.gov.br',
    'coord1.matildeabreu@educbarueri.sp.gov.br',
    'dir.marlene@educbarueri.sp.gov.br',
    'orient3.fioravantebarletta@educbarueri.sp.gov.br',
    'contato.osmariajose@educbarueri.sp.gov.br',
    'coord1.ivani@educbarueri.sp.gov.br',
    'dir.joaotiburcio@educbarueri.sp.gov.br',
    'orient1.eneias@educbarueri.sp.gov.br',
    'orient1.ezio@educbarueri.sp.gov.br',
    'orient2.padreluiz@educbarueri.sp.gov.br',
    'coord1.ricardopeagno@educbarueri.sp.gov.br',
    'dir.raposo@educbarueri.sp.gov.br',
    'dap.ingles@educbarueri.sp.gov.br',
    'coord2.alexandrino@educbarueri.sp.gov.br',
    'coord2.mariajosedebarros@educbarueri.sp.gov.br',
    'orient3.osvaldo@educbarueri.sp.gov.br',
    'dir.padreluiz@educbarueri.sp.gov.br',
    'orient1.aristides@educbarueri.sp.gov.br',
    'coord1.joaofernandes@educbarueri.sp.gov.br',
    'coord1.mariobezerra@educbarueri.sp.gov.br',
    'dir.gilbertoflorencio@educbarueri.sp.gov.br',
    'orient1.padreelidio@educbarueri.sp.gov.br',
    'orient2.dalva@educbarueri.sp.gov.br',
    'orient2.onofra@educbarueri.sp.gov.br',
    'orient2.suzete@educbarueri.sp.gov.br',
    'coord3.osvaldo@educbarueri.sp.gov.br',
    'cme.apoio@educbarueri.sp.gov.br',
    'orient1.armandocavazza@educbarueri.sp.gov.br',
    'coord1.dorival@educbarueri.sp.gov.br',
    'dir.mariameduneckas.emei@educbarueri.sp.gov.br',
    'dir.osmarinho.emei@educbarueri.sp.gov.br',
    'orient2.mariojoaquim@educbarueri.sp.gov.br',
    'dir.joseleandro@educbarueri.sp.gov.br',
    'orient1.franciscozacarioto@educbarueri.sp.gov.br',
    'coord1.margarida@educbarueri.sp.gov.br',
    'dir.elizabeth@educbarueri.sp.gov.br',
    'dir.lenio@educbarueri.sp.gov.br',
    'orient1.raposo@educbarueri.sp.gov.br',
    'orient2.alcino@educbarueri.sp.gov.br',
    'orient2.naly@educbarueri.sp.gov.br',
    'orient1.egidio@educbarueri.sp.gov.br',
    'dir.armandocavazza@educbarueri.sp.gov.br',
    'dir.ricardopeagno@educbarueri.sp.gov.br',
    'coord1.taromizutori@educbarueri.sp.gov.br',
    'dir.jorgeaugusto@educbarueri.sp.gov.br',
    'coord1.elvira@educbarueri.sp.gov.br',
    'dir.beneditovenancio@educbarueri.sp.gov.br',
    'orient1.joaocarvalho@educbarueri.sp.gov.br',
    'pma.musicacap@educbarueri.sp.gov.br',
    'coord1.elianecastanon@educbarueri.sp.gov.br',
    'coord1.marisaodaine@educbarueri.sp.gov.br',
    'coord3.fioravantebarletta@educbarueri.sp.gov.br',
    'dap.portugues@educbarueri.sp.gov.br',
    'dir.eneias@educbarueri.sp.gov.br',
    'orient2.fioravantebarletta@educbarueri.sp.gov.br',
    'orient2.nestor@educbarueri.sp.gov.br',
    'orient1.joaodealmeida@educbarueri.sp.gov.br',
    'orient1.mariaelisachaluppe@educbarueri.sp.gov.br',
    'coord1.padrerenaldo@educbarueri.sp.gov.br',
    'dir.juliogomescamisao@educbarueri.sp.gov.br',
    'dir.egidio@educbarueri.sp.gov.br',
    'dir.elianecastanon@educbarueri.sp.gov.br',
    'dir.mariobezerra@educbarueri.sp.gov.br',
    'dir.yojirotakaoka@educbarueri.sp.gov.br',
    'orient1.alcino@educbarueri.sp.gov.br',
    'orient3.amador@educbarueri.sp.gov.br',
    'dir.ezio@educbarueri.sp.gov.br',
    'dir.mariamedunekas.emef@educbarueri.sp.gov.br',
    'dir.padreelidio@educbarueri.sp.gov.br',
    'dir.annairene@educbarueri.sp.gov.br',
    'dir.mariojoaquim@educbarueri.sp.gov.br',
    'coord3.yojirotakaoka@educbarueri.sp.gov.br',
    'orient2.joaocarvalho@educbarueri.sp.gov.br',
    'dap.fund1@educbarueri.sp.gov.br',
    'dir.mariadolores@educbarueri.sp.gov.br',
    'orient2.elizabeth@educbarueri.sp.gov.br',
    'orient2.mariamedunekas.emef@educbarueri.sp.gov.br',
    'coord1.beneditoadherbal@educbarueri.sp.gov.br',
    'orient2.beneditoadherbal@educbarueri.sp.gov.br',
    'dap.resultados@educbarueri.sp.gov.br',
    'dir.margarida@educbarueri.sp.gov.br',
    'dir.rogeliolopez@educbarueri.sp.gov.br',
    'coord1.osmarinho.emm@educbarueri.sp.gov.br',
    'sec2.elianecastanon@educbarueri.sp.gov.br',
    'coord3.taromizutori@educbarueri.sp.gov.br',
    'contato.osvitoria@educbarueri.sp.gov.br',
    'dir.joaocarvalho@educbarueri.sp.gov.br',
    'sec1.rogeliolopez@educbarueri.sp.gov.br',
    'dir.deiro@educbarueri.sp.gov.br',
    'dir.joaobatistapazinato@educbarueri.sp.gov.br',
    'orient1.yojirotakaoka@educbarueri.sp.gov.br',
    'coord3.amador@educbarueri.sp.gov.br',
    'dir.takechitakau@educbarueri.sp.gov.br',
    'orient1.fioravantebarletta@educbarueri.sp.gov.br',
    'orient1.ritadecassia@educbarueri.sp.gov.br',
    'orient3.ritadejesus@educbarueri.sp.gov.br',
    'dir.eminoldo@educbarueri.sp.gov.br',
    'dir.ritadecassia@educbarueri.sp.gov.br',
    'orient1.eizaburonomura@educbarueri.sp.gov.br',
    'orient2.eneias@educbarueri.sp.gov.br',
    'coord3.alexandrino@educbarueri.sp.gov.br',
    'coord4.dorival@educbarueri.sp.gov.br',
    'dir.deciotrujillo@educbarueri.sp.gov.br',
    'dir.joaofernandes@educbarueri.sp.gov.br',
    'dir.mariaandrelina@educbarueri.sp.gov.br',
    'dir.mariaelisachaluppe@educbarueri.sp.gov.br',
    'contato.osnadir@educbarueri.sp.gov.br',
    'contato.annairene@educbarueri.sp.gov.br',
    'coord4.beneditoadherbal@educbarueri.sp.gov.br',
    'dap.filosofia@educbarueri.sp.gov.br',
    'dir.roquesoares@educbarueri.sp.gov.br',
    'cef.adm@educbarueri.sp.gov.br',
    'dap.musica@educbarueri.sp.gov.br',
    'sec1.naly@educbarueri.sp.gov.br',
    'dir.robertoluis@educbarueri.sp.gov.br',
    'dos@educbarueri.sp.gov.br',
    'orient2.alexandrino@educbarueri.sp.gov.br',
    'orient2.joseleandro@educbarueri.sp.gov.br',
    'orient3.nestor@educbarueri.sp.gov.br',
    'sec1.eizaburonomura@educbarueri.sp.gov.br',
    'dir.levy@educbarueri.sp.gov.br',
    'dir.nestor@educbarueri.sp.gov.br',
    'dir.suzete@educbarueri.sp.gov.br',
    'orient1.taromizutori@educbarueri.sp.gov.br',
    'dae.silvanahaponczyk@educbarueri.sp.gov.br',
    'dir.agenor@educbarueri.sp.gov.br',
    'dir.elvira@educbarueri.sp.gov.br',
    'dir.mariameduneckas.emm@educbarueri.sp.gov.br',
    'orient1.ritadejesus@educbarueri.sp.gov.br',
    'orient2.margarida@educbarueri.sp.gov.br',
    'sec1.alfredo@educbarueri.sp.gov.br',
    'sec1.egidio@educbarueri.sp.gov.br',
    'abonoprodutividade@educbarueri.sp.gov.br',
    'coord3.ritadejesus@educbarueri.sp.gov.br',
    'contato.osmarly@educbarueri.sp.gov.br',
    'dir.mariarosa@educbarueri.sp.gov.br',
    'orient1.nestor@educbarueri.sp.gov.br',
    'contato.beneditovenancio@educbarueri.sp.gov.br',
    'call.center@educbarueri.sp.gov.br',
    'cestabasica@educbarueri.sp.gov.br',
    'conesul1@educbarueri.sp.gov.br',
    'contato.alexandrino@educbarueri.sp.gov.br',
    'contato.aparecidaakyama@educbarueri.sp.gov.br',
    'contato.aristides@educbarueri.sp.gov.br',
    'contato.armandocavazza@educbarueri.sp.gov.br',
    'contato.beneditoadherbal@educbarueri.sp.gov.br',
    'contato.brunotolaini@educbarueri.sp.gov.br',
    'contato.deciotrujillo@educbarueri.sp.gov.br',
    'contato.eizaburonomura@educbarueri.sp.gov.br',
    'contato.elainecalsolari@educbarueri.sp.gov.br',
    'contato.elianecastanon@educbarueri.sp.gov.br',
    'contato.eneias@educbarueri.sp.gov.br',
    'contato.estevan@educbarueri.sp.gov.br',
    'contato.fioravantebarletta@educbarueri.sp.gov.br',
    'contato.franciscozacarioto@educbarueri.sp.gov.br',
    'contato.gilbertoflorencio@educbarueri.sp.gov.br',
    'contato.ivani@educbarueri.sp.gov.br',
    'contato.joaobatistapazinato@educbarueri.sp.gov.br',
    'contato.joaocarvalho@educbarueri.sp.gov.br',
    'contato.joaodealmeida@educbarueri.sp.gov.br',
    'contato.joaoevangelista@educbarueri.sp.gov.br',
    'contato.joaofernandes@educbarueri.sp.gov.br',
    'contato.joaquimsoares@educbarueri.sp.gov.br',
    'contato.jorgeaugusto@educbarueri.sp.gov.br',
    'contato.josedomingos@educbarueri.sp.gov.br',
    'contato.joseleandro@educbarueri.sp.gov.br',
    'contato.juliogomescamisao@educbarueri.sp.gov.br',
    'contato.lenio@educbarueri.sp.gov.br',
    'contato.leonardoaugusto@educbarueri.sp.gov.br',
    'contato.lucineia@educbarueri.sp.gov.br',
    'contato.margarida@educbarueri.sp.gov.br',
    'contato.mariaandrelina@educbarueri.sp.gov.br',
    'contato.mariademenezes@educbarueri.sp.gov.br',
    'contato.mariadolores@educbarueri.sp.gov.br',
    'contato.mariaelisachaluppe@educbarueri.sp.gov.br',
    'contato.mariajosedebarros@educbarueri.sp.gov.br',
    'contato.mariameduneckas.emef@educbarueri.sp.gov.br',
    'contato.mariameduneckas.maternal@educbarueri.sp.gov.br',
    'contato.mariarosa@educbarueri.sp.gov.br',
    'contato.mariobezerra@educbarueri.sp.gov.br',
    'contato.mariojoaquim@educbarueri.sp.gov.br',
    'contato.marisaodaine@educbarueri.sp.gov.br',
    'contato.marlene@educbarueri.sp.gov.br',
    'contato.matildeabreu@educbarueri.sp.gov.br',
    'contato.onofra@educbarueri.sp.gov.br',
    'contato.osaracy@educbarueri.sp.gov.br',
    'contato.osegle@educbarueri.sp.gov.br',
    'contato.osguilherme@educbarueri.sp.gov.br',
    'contato.osmariadocarmo@educbarueri.sp.gov.br',
    'contato.osmarinho.emef@educbarueri.sp.gov.br',
    'contato.osmarinho.emei@educbarueri.sp.gov.br',
    'contato.osmarinho.maternal@educbarueri.sp.gov.br',
    'contato.osvaldo@educbarueri.sp.gov.br',
    'contato.osvaledosol@educbarueri.sp.gov.br',
    'contato.oswandeir@educbarueri.sp.gov.br',
    'contato.padreelidio@educbarueri.sp.gov.br',
    'contato.padreluiz@educbarueri.sp.gov.br',
    'contato.padrerenaldo@educbarueri.sp.gov.br',
    'contato.primeirainfancia@educbarueri.sp.gov.br',
    'contato.raposo@educbarueri.sp.gov.br',
    'contato.renatorosa@educbarueri.sp.gov.br',
    'contato.ricardopeagno@educbarueri.sp.gov.br',
    'contato.ritadecassia@educbarueri.sp.gov.br',
    'contato.robertogritti@educbarueri.sp.gov.br',
    'contato.robertoluiz@educbarueri.sp.gov.br',
    'contato.roquesoares@educbarueri.sp.gov.br',
    'contato.sandroluiz@educbarueri.sp.gov.br',
    'contato.sidneysantucci@educbarueri.sp.gov.br',
    'contato.suzete@educbarueri.sp.gov.br',
    'contato.thomazvictoria@educbarueri.sp.gov.br',
    'contato.valdineia@educbarueri.sp.gov.br',
    'contato.yojirotakaoka@educbarueri.sp.gov.br',
    'coord1.joaoevangelista@educbarueri.sp.gov.br',
    'coord1.osvaledosol@educbarueri.sp.gov.br',
    'coord1.osvitoria@educbarueri.sp.gov.br',
    'crpi.saber@educbarueri.sp.gov.br',
    'dee.pss@educbarueri.sp.gov.br',
    'dir.alfredo@educbarueri.sp.gov.br',
    'dir.aparecidaakyama@educbarueri.sp.gov.br',
    'dir.franciscozacarioto@educbarueri.sp.gov.br',
    'dir.leonardoaugusto@educbarueri.sp.gov.br',
    'dir.robertogriti@educbarueri.sp.gov.br',
    'dir.teste@educbarueri.sp.gov.br',
    'edu.psicoped14@educbarueri.sp.gov.br',
    'edu.psicoped25@educbarueri.sp.gov.br',
    'edu.psicoped27@educbarueri.sp.gov.br',
    'edu.psicoped28@educbarueri.sp.gov.br',
    'edu.supervisao3@educbarueri.sp.gov.br',
    'edu.supervisao4@educbarueri.sp.gov.br',
    'educ.dpc02@educbarueri.sp.gov.br',
    'ei.pss@educbarueri.sp.gov.br',
    'googleforeducation@educbarueri.sp.gov.br',
    'hackathon@educbarueri.sp.gov.br',
    'integrador@educbarueri.sp.gov.br',
    'olhonaescola.anderson@educbarueri.sp.gov.br',
    'olhonaescola.liliane@educbarueri.sp.gov.br',
    'olhonaescola.rosangela@educbarueri.sp.gov.br',
    'orient1.elvira@educbarueri.sp.gov.br',
    'orient1.levy@educbarueri.sp.gov.br',
    'orient1.osvaldo@educbarueri.sp.gov.br',
    'orient2.elvira@educbarueri.sp.gov.br',
    'orient2.ezio@educbarueri.sp.gov.br',
    'orient3.levy@educbarueri.sp.gov.br',
    'redessociais@educbarueri.sp.gov.br',
    'sec.mariaelisa@educbarueri.sp.gov.br',
    'sec1.agenor@educbarueri.sp.gov.br',
    'sec1.alcino@educbarueri.sp.gov.br',
    'sec1.alexandrino@educbarueri.sp.gov.br',
    'sec1.annairene@educbarueri.sp.gov.br',
    'sec1.armandocavazza@educbarueri.sp.gov.br',
    'sec1.beneditovenancio@educbarueri.sp.gov.br',
    'sec1.brunotolaini@educbarueri.sp.gov.br',
    'sec1.dalva@educbarueri.sp.gov.br',
    'sec1.deciotrujillo@educbarueri.sp.gov.br',
    'sec1.deiro@educbarueri.sp.gov.br',
    'sec1.dorival@educbarueri.sp.gov.br',
    'sec1.elizabeth@educbarueri.sp.gov.br',
    'sec1.eminoldo@educbarueri.sp.gov.br',
    'sec1.eneias@educbarueri.sp.gov.br',
    'sec1.estevan@educbarueri.sp.gov.br',
    'sec1.franciscozacarioto@educbarueri.sp.gov.br',
    'sec1.ivani@educbarueri.sp.gov.br',
    'sec1.joaoalmeida@educbarueri.sp.gov.br',
    'sec1.joaobatistapazinato@educbarueri.sp.gov.br',
    'sec1.joaoevangelista@educbarueri.sp.gov.br',
    'sec1.joaofernandes@educbarueri.sp.gov.br',
    'sec1.joaquimsoares@educbarueri.sp.gov.br',
    'sec1.jorgeaugusto@educbarueri.sp.gov.br',
    'sec1.josedomingos@educbarueri.sp.gov.br',
    'sec1.josevital@educbarueri.sp.gov.br',
    'sec1.juliogomescamisao@educbarueri.sp.gov.br',
    'sec1.lenio@educbarueri.sp.gov.br',
    'sec1.lucineia@educbarueri.sp.gov.br',
    'sec1.luziadasgracas@educbarueri.sp.gov.br',
    'sec1.margarida@educbarueri.sp.gov.br',
    'sec1.mariaandrelina@educbarueri.sp.gov.br',
    'sec1.mariademenezes@educbarueri.sp.gov.br',
    'sec1.mariadolores@educbarueri.sp.gov.br',
    'sec1.mariajosedebarros@educbarueri.sp.gov.br',
    'sec1.mariameduneckas.emei@educbarueri.sp.gov.br',
    'sec1.mariamedunekas.emef@educbarueri.sp.gov.br',
    'sec1.mariarosa@educbarueri.sp.gov.br',
    'sec1.mariobezerra@educbarueri.sp.gov.br',
    'sec1.mariojoaquim@educbarueri.sp.gov.br',
    'sec1.marisaodaine@educbarueri.sp.gov.br',
    'sec1.marlene@educbarueri.sp.gov.br',
    'sec1.nestor@educbarueri.sp.gov.br',
    'sec1.osmarinho.emef@educbarueri.sp.gov.br',
    'sec1.osmarinho.emei@educbarueri.sp.gov.br',
    'sec1.osmarinho.emm@educbarueri.sp.gov.br',
    'sec1.padreelidio@educbarueri.sp.gov.br',
    'sec1.padrerenaldo@educbarueri.sp.gov.br',
    'sec1.renatorosa@educbarueri.sp.gov.br',
    'sec1.ritadejesus@educbarueri.sp.gov.br',
    'sec1.robertoluis@educbarueri.sp.gov.br',
    'sec1.sidneysantucci@educbarueri.sp.gov.br',
    'sec1.suzete@educbarueri.sp.gov.br',
    'sec1.thomazvictoria@educbarueri.sp.gov.br',
    'sec1.valdineia@educbarueri.sp.gov.br',
    'sec2.alexandrino@educbarueri.sp.gov.br',
    'sec2.alfredo@educbarueri.sp.gov.br',
    'sec2.deciotrujillo@educbarueri.sp.gov.br',
    'sec2.deiro@educbarueri.sp.gov.br',
    'sec2.dorival@educbarueri.sp.gov.br',
    'sec2.elizabeth@educbarueri.sp.gov.br',
    'sec2.estevan@educbarueri.sp.gov.br',
    'sec2.gilbertoflorencio@educbarueri.sp.gov.br',
    'sec2.ivani@educbarueri.sp.gov.br',
    'sec2.joaoalmeida@educbarueri.sp.gov.br',
    'sec2.joaoevangelista@educbarueri.sp.gov.br',
    'sec2.levy@educbarueri.sp.gov.br',
    'sec2.margarida@educbarueri.sp.gov.br',
    'sec2.mariademenezes@educbarueri.sp.gov.br',
    'sec2.mariajosedebarros@educbarueri.sp.gov.br',
    'sec2.mariameduneckas.emm@educbarueri.sp.gov.br',
    'sec2.marisaodaine@educbarueri.sp.gov.br',
    'sec2.osmarinho.emm@educbarueri.sp.gov.br',
    'sec2.padreelidio@educbarueri.sp.gov.br',
    'sec2.renatorosa@educbarueri.sp.gov.br',
    'sec2.sidneysantucci@educbarueri.sp.gov.br',
    'sec2.yojirotakaoka@educbarueri.sp.gov.br',
    'sec3.alexandrino@educbarueri.sp.gov.br',
    'sec3.deiro@educbarueri.sp.gov.br',
    'sec3.dorival@educbarueri.sp.gov.br',
    'sec3.estevan@educbarueri.sp.gov.br',
    'sec3.joaocarvalho@educbarueri.sp.gov.br',
    'sec3.mariadolores@educbarueri.sp.gov.br',
    'sec3.mariameduneckas.emm@educbarueri.sp.gov.br',
    'sec3.padreelidio@educbarueri.sp.gov.br',
    'sec3.yojirotakaoka@educbarueri.sp.gov.br',
    'sec4.mariameduneckas.emm@educbarueri.sp.gov.br',
    'secretariaa@educbarueri.sp.gov.br',
    'sieb@educbarueri.sp.gov.br',
    'orient1.alexandrino@educbarueri.sp.gov.br',
    'agenda@educbarueri.sp.gov.br',
    'coord3.nestor@educbarueri.sp.gov.br',
    'dir.joaodealmeida@educbarueri.sp.gov.br',
    'dir.matildeabreu@educbarueri.sp.gov.br',
    'dir.osmarinho.emm@educbarueri.sp.gov.br',
    'dir.padrerenaldo@educbarueri.sp.gov.br',
    'dir.sidneysantucci@educbarueri.sp.gov.br',
    'dir.taromizutori@educbarueri.sp.gov.br',
    'orient2.deiro@educbarueri.sp.gov.br',
    'sec2.fioravantebarletta@educbarueri.sp.gov.br',
    'orient2.osvaldo@educbarueri.sp.gov.br',
    'contato.osluzia@educbarueri.sp.gov.br',
    'contato.rogeliolopez@educbarueri.sp.gov.br',
    'coord1.alexandrino@educbarueri.sp.gov.br',
    'dir.aristides@educbarueri.sp.gov.br',
    'sec1.mariameduneckas.emm@educbarueri.sp.gov.br',
    'sec1.onofra@educbarueri.sp.gov.br',
    'dir.alexandrino@educbarueri.sp.gov.br',
    'cei.adm@educbarueri.sp.gov.br',
    'contato.alfredo@educbarueri.sp.gov.br',
    'contato.deiro@educbarueri.sp.gov.br',
    'contato.egidio@educbarueri.sp.gov.br',
    'contato.mariameduneckas.emei@educbarueri.sp.gov.br',
    'contato.oslazara@educbarueri.sp.gov.br',
    'contato.ritadejesus@educbarueri.sp.gov.br',
    'coord1.joaquimsoares@educbarueri.sp.gov.br',
    'ef.pss@educbarueri.sp.gov.br',
    'sec1.joaocarvalho@educbarueri.sp.gov.br',
    'contato.joaotiburcio@educbarueri.sp.gov.br',
    'contato.joseemidio@educbarueri.sp.gov.br',
    'contato.oscleide@educbarueri.sp.gov.br',
    'sec1.elianecastanon@educbarueri.sp.gov.br',
    'sec1.joaotiburcio@educbarueri.sp.gov.br',
    'sec1.raposo@educbarueri.sp.gov.br',
    'agp.eqalpha@educbarueri.sp.gov.br',
    'agp.eqbravo@educbarueri.sp.gov.br',
    'contato.dalva@educbarueri.sp.gov.br',
    'contato.ezio@educbarueri.sp.gov.br',
    'contato.naly@educbarueri.sp.gov.br',
    'contato.nestor@educbarueri.sp.gov.br',
    'contato.osnelson@educbarueri.sp.gov.br',
    'contato.taromizutori@educbarueri.sp.gov.br',
    'dir.ritadejesus@educbarueri.sp.gov.br',
    'orient2.raposo@educbarueri.sp.gov.br',
    'orient3.beneditoadherbal@educbarueri.sp.gov.br',
    'sec1.gilbertoflorencio@educbarueri.sp.gov.br',
    'sec1.joseemidio@educbarueri.sp.gov.br',
    'sec1.leonardoaugusto@educbarueri.sp.gov.br',
    'sec1.levy@educbarueri.sp.gov.br',
    'sec1.ritadecassia@educbarueri.sp.gov.br',
    'sec1.roquesoares@educbarueri.sp.gov.br',
    'agp.eqcharlie@educbarueri.sp.gov.br',
    'agp.eqdelta@educbarueri.sp.gov.br',
    'contato.alcino@educbarueri.sp.gov.br',
    'contato.amador@educbarueri.sp.gov.br',
    'contato.dorival@educbarueri.sp.gov.br',
    'contato.elizabeth@educbarueri.sp.gov.br',
    'contato.elvira@educbarueri.sp.gov.br',
    'contato.eminoldo@educbarueri.sp.gov.br',
    'contato.josevital@educbarueri.sp.gov.br',
    'contato.levy@educbarueri.sp.gov.br',
    'contato.osevelyn@educbarueri.sp.gov.br',
    'contato.takechitakau@educbarueri.sp.gov.br',
    'orient1.jorgeaugusto@educbarueri.sp.gov.br',
    'orient1.sandroluiz@educbarueri.sp.gov.br',
    'orient2.aristides@educbarueri.sp.gov.br',
    'sec1.aristides@educbarueri.sp.gov.br',
    'sec1.beneditoadherbal@educbarueri.sp.gov.br',
    'sec1.ezio@educbarueri.sp.gov.br',
    'sec1.fioravantebarletta@educbarueri.sp.gov.br',
    'sec1.ricardopeagno@educbarueri.sp.gov.br',
    'sec1.sandroluiz@educbarueri.sp.gov.br',
    'sec2.joaocarvalho@educbarueri.sp.gov.br',
    'sec2.joaotiburcio@educbarueri.sp.gov.br',
    'sec2.jorgeaugusto@educbarueri.sp.gov.br',
    'sec2.nestor@educbarueri.sp.gov.br',
    'sec2.onofra@educbarueri.sp.gov.br',
    'sec3.joaoalmeida@educbarueri.sp.gov.br',
];
$emailPrefixo = explode('.', $email)[0];
if (in_array($emailFixo, ['professor.barueri.br', 'educbarueri.sp.gov.br'])) {
    //  if (in_array($emailPrefixo, $prefixo) || in_array($email, $emailNo)) {
    ?>
    <!--
            <div class="alert alert-danger">
                O prefixo "<?= $emailPrefixo ?>" não é válido. Utilize seu email institucional NOMINAL, terminado em @professor.barueri.br ou @educbarueri.sp.gov.br.
            </div>
    -->
    <?php
    //  } else {
    $p = sql::get('pessoa', 'id_pessoa, n_pessoa', ['emailgoogle' => $email], 'fetch');
    if (!empty($p)) {
        $nome = $p['n_pessoa'];
        $id_pessoa = $p['id_pessoa'];

        $sql = "SELECT c.id_ch, c.serial, m.n_cm FROM `lab_chrome` c "
                . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null  "
                . " join pessoa p on p.id_pessoa = e.fk_id_pessoa and emailgoogle LIKE '" . trim($email) . "' ";
        /**
          $sql = "SELECT c.id_ch, c.serial, m.n_cm FROM `lab_chrome` c "
          . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
          . "WHERE `email_google` LIKE '" . trim($email) . "' "
          . ' and recadastro != 1 '
          . ' and fk_id_cd not in (1, 3) ';
         * * */
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="border">
            <p>Olá, <?= ucfirst(strtolower(explode(' ', $nome)[0])) ?></p>
            <br />
            <?php
            if (!empty($array)) {
                if (count($array) > 1) {
                    ?>
                    <p>
                        Encontramos esses Chormebooks em que Você logou.
                    </p>
                    <br />
                    <p>
                        Verifique pelo número de série qual está sob sua responsabilidade.
                    </p>
                    <br />
                    <p>
                        O número de série está na parte de de baixo do seu Chromebook conforme mostra a imagem acima.
                    </p>
                    <br /><br />
                    <?php
                } else {
                    ?>
                    <p>
                        Encontramos esse Chormebooks em que Você logou.
                    </p>
                    <br />
                    <p>
                        Verifique pelo número de série se é este que está sob sua responsabilidade.
                    </p>
                    <br />
                    <p>
                        O número de série está na parte de de baixo do seu Chromebook conforme mostra a imagem acima.
                    </p>
                    <br /><br />
                    <?php
                }
                foreach ($array as $v) {
                    $serial[] = $v['serial'];
                    ?>

                    <table>
                        <tr>
                            <td class="border" style="padding: 6px; ">
                                <table>
                                    <tr>
                                        <td>
                                            Número de série: <span style="color: red; font-weight: bold"><?= $v['serial'] ?></span>
                                        </td>
                                        <td>&nbsp;&nbsp;</td>
                                        <td>
                                            Modelo: <?= $v['n_cm'] ?>    
                                        </td>
                                        <td>&nbsp;&nbsp;</td>
                                        <?php
                                        if (count($array) == 1) {
                                            /**
                                              ?>
                                              <td>
                                              <form id="del<?= $v['id_ch'] ?>" method="POST">
                                              <?=
                                              formErp::hidden([
                                              'id_pessoa' => $id_pessoa,
                                              'email' => $email,
                                              'serials' => "'" . implode("','", $serial) . "'",
                                              'nome' => $nome
                                              ])
                                              . formErp::hiddenToken('chromeProfDelTodos')
                                              ?>
                                              </form>

                                              <button onclick="if (confirm('Retirar o chromebook <?= $v['serial'] ?> da sua lista?')) {
                                              document.getElementById('del<?= $v['id_ch'] ?>').submit()
                                              }" class="btn btn-danger">
                                              NÃO é este
                                              </button>
                                              </td>
                                              <td>&nbsp;&nbsp;</td>

                                              <?php
                                             * 
                                             */
                                        }
                                        ?>
                                        <td>
                                            <?php
                                            if (false) {
                                                ?>
                                                <form id="add<?= $v['id_ch'] ?>" method="POST">
                                                    tag
                                                    <?=
                                                    formErp::hidden([
                                                        'id_ch' => $v['id_ch'],
                                                        'email' => $email,
                                                        'id_pessoa' => $id_pessoa,
                                                        'serial' => $v['serial'],
                                                        'nome' => $nome
                                                    ])
                                                    . formErp::hiddenToken('chromeProfEdit')
                                                    ?>
                                                    <button onclick="if (confirm('O chromebook <?= $v['serial'] ?> está sob sua responsabilidade?')) {
                                                                document.getElementById('add<?= $v['id_ch'] ?>').submit()
                                                            }" class="btn btn-success">
                                                        SIM, é este
                                                    </button>
                                                </form>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <br /><br />
                    <?php
                }
                if (count($array) > 1) {
                    /**
                      ?>
                      <table>
                      <tr>
                      <td class="border" style="padding: 6px; ">
                      <form id="del" method="POST">
                      <?=
                      formErp::hidden([
                      'id_pessoa' => $id_pessoa,
                      'email' => $email,
                      'serials' => implode(",", $serial),
                      'nome' => $nome
                      ])
                      . formErp::hiddenToken('chromeProfDelTodos')
                      ?>
                      </form>

                      <button onclick="if (confirm('Nenhum Chromebook relacionado acima está sob minha responsabilidade?')) {
                      document.getElementById('del').submit()
                      }" class="btn btn-danger">
                      Nenhum Chromebook relacionado acima está sob minha responsabilidade
                      </button>
                      </td>
                      </tr>
                      </table>
                      <?php
                     * 
                     */
                }
            } else {
                /**
                  $sql = "SELECT c.id_ch, c.serial, m.n_cm FROM `lab_chrome` c "
                  . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                  . "WHERE `email_google` LIKE '" . trim($email) . "' "
                  . ' and recadastro = 1';
                 * 
                 */
                $sql = "SELECT c.id_ch, c.serial, m.n_cm FROM `lab_chrome` c "
                        . " join lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                        . " join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null  "
                        . " join pessoa p on p.id_pessoa = e.fk_id_pessoa and emailgoogle LIKE '" . trim($email) . "' ";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($array)) {
                    if (count($array) > 1) {
                        ?>
                        <p>
                            Os Chromebooks abaixo estão relacionados em nosso sistema, como sendo de sua responsabilidade.
                        </p>

                        <?php
                    } else {
                        ?>
                        <p>
                            O Chromebook abaixo está relacionado em nosso sistema, como sendo de sua responsabilidade.
                        </p>
                        <?php
                    }
                    ?>
                    <br />
                    <p>
                        Havendo alguma irregularidade, abra uma ocorrência.
                    </p>
                    <br /><br />
                    <?php
                    foreach ($array as $v) {
                        ?>
                        <table>
                            <tr>
                                <td class="border" style="padding: 6px; ">
                                    <table>
                                        <tr>
                                            <td>
                                                Número de séria: <span style="color: red; font-weight: bold"><?= $v['serial'] ?></span>
                                            </td>
                                            <td>&nbsp;&nbsp;</td>
                                            <td>
                                                Modelo: <?= $v['n_cm'] ?>    
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    &nbsp;&nbsp;
                                </td>
                                <?php
                                $sql = "SELECT serial FROM `lab_chrome_critica` WHERE `serial` LIKE '" . $v['serial'] . "' AND `respondida` != 1 ";
                                $query = pdoSis::getInstance()->query($sql);
                                @$srl = $query->fetch(PDO::FETCH_ASSOC)['serial'];
                                /**
                                  if (empty($srl)) {
                                  ?>
                                  <td>
                                  <button onclick="ocorre(<?= $v['id_ch'] ?>)" class="btn btn-danger">
                                  Abrir Ocorrência
                                  </button>
                                  </td>
                                  <?php
                                  } else {
                                  ?>
                                  <td>
                                  <button onclick="alert('Aguarde o contato da Secretaria de Educação')" class="btn btn-warning">
                                  Em Análise
                                  </button>
                                  </td>
                                  <?php
                                  }
                                 * 
                                 */
                                ?>
                                <td>
                                    &nbsp;&nbsp;
                                </td>
                                <td>
                                    <form action="<?= HOME_URI ?>/lab/protProf" target="_blank" method="POST">
                                        <?=
                                        formErp::hidden([
                                            'email' => $email,
                                            'id_ch' => $v['id_ch']
                                        ]);
                                        ?>
                                        <button type="submit" class="btn btn-info">
                                            Termo de Responsabilidade
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <br /><br />
                        <?php
                    }
                    ?>
                    <div id="sn">
                        <p>
                            Tem mais algum chromebook sob sua responsabilidade?
                        </p>
                        <br /><br />
                        <div class="text-center">
                            <button class="btn btn-success" onclick="document.getElementById('maisCh').style.display = '';
                                    document.getElementById('sn').style.display = 'none';">
                                Incluir um outro Chromebook
                            </button>
                        </div>
                    </div>
                    <br />
                    <?php
                    $display = 'none';
                } else {
                    $display = 'block';
                }
                ?>
                <br />
                <div id="maisCh" style="display:<?= $display ?>">
                    <p>
                        Inclua, utilizando o formulário abaixo, o número de série do chromebook que está sob sua responsabilidade.
                    </p>
                    <br />
                    <p>
                        O número de série está na parte de baixo do seu Chromebook conforme mostra a imagem acima.
                    </p>
                    <br /><br />
                    <form method="POST">
                        <table>
                            <tr>
                                <td style="min-width: 500px">
                                    <?= formErp::input('1[serial]', 'Número de Série', null, '  style="text-transform:uppercase" required') ?>
                                </td>
                                <td>
                                    &nbsp;&nbsp; 
                                </td>
                                <td style="width: 10px">
                                    <?=
                                    formErp::hidden([
                                        '1[fk_id_pessoa_lanc]' => $id_pessoa,
                                        '1[fk_id_pessoa]' => $id_pessoa,
                                        '1[email_google]' => $email,
                                        'nome' => $nome
                                    ])
                                    . formErp::hiddenToken('chromeProfnovo')
                                    . formErp::button('Cadastrar')
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger">
                Seu email é válido, porém não o encontramos relacionado a um usuário.
                Em caso de dúvida, contacte o depto de Informática.
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    //  }
} else {
    ?>
    <div class="alert alert-danger">
        São válidos apenas os e-mails nominais dos domínios "educbarueri.sp.gov.br" e "professor.barueri.br"
    </div>
    <?php
}
