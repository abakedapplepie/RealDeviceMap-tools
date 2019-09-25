<?php
error_reporting(1);
define('DB_HOST', "127.0.0.1");
define('DB_USER', "rdmuser");
define('DB_PSWD', "password");
define('DB_NAME', "rdmdb");
define('DB_PORT', 3306);
if ($_POST['data']) { map_helper_init(); } else { ?><!DOCTYPE html>
<html>
  <head>
    <title>RealDeviceMap Toolbox</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body {
        height: 100%;
      }
      #map {
        height: 100%;
      }
      .modal-loader .modal-dialog{
        display: table;
        position: relative;
        margin: 0 auto;
        top: calc(50% - 24px);
      }
      .modal-loader .modal-dialog .modal-content{
        background-color: transparent;
        border: none;
      }
      .nestName {
        min-width: 175px;
        text-align: center;
        font-weight: bold;
      }
      .buttonOff {
        background: #ccc;
      }
      .easy-button-container.disabled, .easy-button-button.disabled {
        display: none;
      }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-easybutton@2.0.0/src/easy-button.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-toolbar@0.4.0-alpha.1/dist/leaflet.toolbar.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.EasyButton/2.3.0/easy-button.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.9.0/src/leaflet.geometryutil.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@5/turf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/osmtogeojson@3.0.0-beta.3/osmtogeojson.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-toolbar@0.4.0-alpha.1/dist/leaflet.toolbar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/s2-geometry@1.2.10/src/s2geometry.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
	  <script src="https://cdn.jsdelivr.net/npm/leaflet-path-drag@1.1.0/dist/L.Path.Drag.js"></script>
    <script type="text/javascript">
    // english names
    var pokemon = ["Bulbasaur","Ivysaur","Venusaur","Charmander","Charmeleon","Charizard","Squirtle","Wartortle","Blastoise","Caterpie","Metapod","Butterfree","Weedle","Kakuna","Beedrill","Pidgey","Pidgeotto","Pidgeot","Rattata","Raticate","Spearow","Fearow","Ekans","Arbok","Pikachu","Raichu","Sandshrew","Sandslash","Nidoran\u2640","Nidorina","Nidoqueen","Nidoran\u2642","Nidorino","Nidoking","Clefairy","Clefable","Vulpix","Ninetales","Jigglypuff","Wigglytuff","Zubat","Golbat","Oddish","Gloom","Vileplume","Paras","Parasect","Venonat","Venomoth","Diglett","Dugtrio","Meowth","Persian","Psyduck","Golduck","Mankey","Primeape","Growlithe","Arcanine","Poliwag","Poliwhirl","Poliwrath","Abra","Kadabra","Alakazam","Machop","Machoke","Machamp","Bellsprout","Weepinbell","Victreebel","Tentacool","Tentacruel","Geodude","Graveler","Golem","Ponyta","Rapidash","Slowpoke","Slowbro","Magnemite","Magneton","Farfetch\u2019d","Doduo","Dodrio","Seel","Dewgong","Grimer","Muk","Shellder","Cloyster","Gastly","Haunter","Gengar","Onix","Drowzee","Hypno","Krabby","Kingler","Voltorb","Electrode","Exeggcute","Exeggutor","Cubone","Marowak","Hitmonlee","Hitmonchan","Lickitung","Koffing","Weezing","Rhyhorn","Rhydon","Chansey","Tangela","Kangaskhan","Horsea","Seadra","Goldeen","Seaking","Staryu","Starmie","Mr. Mime","Scyther","Jynx","Electabuzz","Magmar","Pinsir","Tauros","Magikarp","Gyarados","Lapras","Ditto","Eevee","Vaporeon","Jolteon","Flareon","Porygon","Omanyte","Omastar","Kabuto","Kabutops","Aerodactyl","Snorlax","Articuno","Zapdos","Moltres","Dratini","Dragonair","Dragonite","Mewtwo","Mew","Chikorita","Bayleef","Meganium","Cyndaquil","Quilava","Typhlosion","Totodile","Croconaw","Feraligatr","Sentret","Furret","Hoothoot","Noctowl","Ledyba","Ledian","Spinarak","Ariados","Crobat","Chinchou","Lanturn","Pichu","Cleffa","Igglybuff","Togepi","Togetic","Natu","Xatu","Mareep","Flaaffy","Ampharos","Bellossom","Marill","Azumarill","Sudowoodo","Politoed","Hoppip","Skiploom","Jumpluff","Aipom","Sunkern","Sunflora","Yanma","Wooper","Quagsire","Espeon","Umbreon","Murkrow","Slowking","Misdreavus","Unown","Wobbuffet","Girafarig","Pineco","Forretress","Dunsparce","Gligar","Steelix","Snubbull","Granbull","Qwilfish","Scizor","Shuckle","Heracross","Sneasel","Teddiursa","Ursaring","Slugma","Magcargo","Swinub","Piloswine","Corsola","Remoraid","Octillery","Delibird","Mantine","Skarmory","Houndour","Houndoom","Kingdra","Phanpy","Donphan","Porygon2","Stantler","Smeargle","Tyrogue","Hitmontop","Smoochum","Elekid","Magby","Miltank","Blissey","Raikou","Entei","Suicune","Larvitar","Pupitar","Tyranitar","Lugia","Ho-Oh","Celebi","Treecko","Grovyle","Sceptile","Torchic","Combusken","Blaziken","Mudkip","Marshtomp","Swampert","Poochyena","Mightyena","Zigzagoon","Linoone","Wurmple","Silcoon","Beautifly","Cascoon","Dustox","Lotad","Lombre","Ludicolo","Seedot","Nuzleaf","Shiftry","Taillow","Swellow","Wingull","Pelipper","Ralts","Kirlia","Gardevoir","Surskit","Masquerain","Shroomish","Breloom","Slakoth","Vigoroth","Slaking","Nincada","Ninjask","Shedinja","Whismur","Loudred","Exploud","Makuhita","Hariyama","Azurill","Nosepass","Skitty","Delcatty","Sableye","Mawile","Aron","Lairon","Aggron","Meditite","Medicham","Electrike","Manectric","Plusle","Minun","Volbeat","Illumise","Roselia","Gulpin","Swalot","Carvanha","Sharpedo","Wailmer","Wailord","Numel","Camerupt","Torkoal","Spoink","Grumpig","Spinda","Trapinch","Vibrava","Flygon","Cacnea","Cacturne","Swablu","Altaria","Zangoose","Seviper","Lunatone","Solrock","Barboach","Whiscash","Corphish","Crawdaunt","Baltoy","Claydol","Lileep","Cradily","Anorith","Armaldo","Feebas","Milotic","Castform","Kecleon","Shuppet","Banette","Duskull","Dusclops","Tropius","Chimecho","Absol","Wynaut","Snorunt","Glalie","Spheal","Sealeo","Walrein","Clamperl","Huntail","Gorebyss","Relicanth","Luvdisc","Bagon","Shelgon","Salamence","Beldum","Metang","Metagross","Regirock","Regice","Registeel","Latias","Latios","Kyogre","Groudon","Rayquaza","Jirachi","Deoxys","Turtwig","Grotle","Torterra","Chimchar","Monferno","Infernape","Piplup","Prinplup","Empoleon","Starly","Staravia","Staraptor","Bidoof","Bibarel","Kricketot","Kricketune","Shinx","Luxio","Luxray","Budew","Roserade","Cranidos","Rampardos","Shieldon","Bastiodon","Burmy","Wormadam","Mothim","Combee","Vespiquen","Pachirisu","Buizel","Floatzel","Cherubi","Cherrim","Shellos","Gastrodon","Ambipom","Drifloon","Drifblim","Buneary","Lopunny","Mismagius","Honchkrow","Glameow","Purugly","Chingling","Stunky","Skuntank","Bronzor","Bronzong","Bonsly","Mime Jr.","Happiny","Chatot","Spiritomb","Gible","Gabite","Garchomp","Munchlax","Riolu","Lucario","Hippopotas","Hippowdon","Skorupi","Drapion","Croagunk","Toxicroak","Carnivine","Finneon","Lumineon","Mantyke","Snover","Abomasnow","Weavile","Magnezone","Lickilicky","Rhyperior","Tangrowth","Electivire","Magmortar","Togekiss","Yanmega","Leafeon","Glaceon","Gliscor","Mamoswine","Porygon-Z","Gallade","Probopass","Dusknoir","Froslass","Rotom","Uxie","Mesprit","Azelf","Dialga","Palkia","Heatran","Regigigas","Giratina","Cresselia","Phione","Manaphy","Darkrai","Shaymin","Arceus","Victini","Snivy","Servine","Serperior","Tepig","Pignite","Emboar","Oshawott","Dewott","Samurott","Patrat","Watchog","Lillipup","Herdier","Stoutland","Purrloin","Liepard","Pansage","Simisage","Pansear","Simisear","Panpour","Simipour","Munna","Musharna","Pidove","Tranquill","Unfezant","Blitzle","Zebstrika","Roggenrola","Boldore","Gigalith","Woobat","Swoobat","Drilbur","Excadrill","Audino","Timburr","Gurdurr","Conkeldurr","Tympole","Palpitoad","Seismitoad","Throh","Sawk","Sewaddle","Swadloon","Leavanny","Venipede","Whirlipede","Scolipede","Cottonee","Whimsicott","Petilil","Lilligant","Basculin","Sandile","Krokorok","Krookodile","Darumaka","Darmanitan","Maractus","Dwebble","Crustle","Scraggy","Scrafty","Sigilyph","Yamask","Cofagrigus","Tirtouga","Carracosta","Archen","Archeops","Trubbish","Garbodor","Zorua","Zoroark","Minccino","Cinccino","Gothita","Gothorita","Gothitelle","Solosis","Duosion","Reuniclus","Ducklett","Swanna","Vanillite","Vanillish","Vanilluxe","Deerling","Sawsbuck","Emolga","Karrablast","Escavalier","Foongus","Amoonguss","Frillish","Jellicent","Alomomola","Joltik","Galvantula","Ferroseed","Ferrothorn","Klink","Klang","Klinklang","Tynamo","Eelektrik","Eelektross","Elgyem","Beheeyem","Litwick","Lampent","Chandelure","Axew","Fraxure","Haxorus","Cubchoo","Beartic","Cryogonal","Shelmet","Accelgor","Stunfisk","Mienfoo","Mienshao","Druddigon","Golett","Golurk","Pawniard","Bisharp","Bouffalant","Rufflet","Braviary","Vullaby","Mandibuzz","Heatmor","Durant","Deino","Zweilous","Hydreigon","Larvesta","Volcarona","Cobalion","Terrakion","Virizion","Tornadus","Thundurus","Reshiram","Zekrom","Landorus","Kyurem","Keldeo","Meloetta","Genesect","Chespin","Quilladin","Chesnaught","Fennekin","Braixen","Delphox","Froakie","Frogadier","Greninja","Bunnelby","Diggersby","Fletchling","Fletchinder","Talonflame","Scatterbug","Spewpa","Vivillon","Litleo","Pyroar","Flabébé","Floette","Florges","Skiddo","Gogoat","Pancham","Pangoro","Furfrou","Espurr","Meowstic","Honedge","Doublade","Aegislash","Spritzee","Aromatisse","Swirlix","Slurpuff","Inkay","Malamar","Binacle","Barbaracle","Skrelp","Dragalge","Clauncher","Clawitzer","Helioptile","Heliolisk","Tyrunt","Tyrantrum","Amaura","Aurorus","Sylveon","Hawlucha","Dedenne","Carbink","Goomy","Sliggoo","Goodra","Klefki","Phantump","Trevenant","Pumpkaboo","Gourgeist","Bergmite","Avalugg","Noibat","Noivern","Xerneas","Yveltal","Zygarde","Diancie","Hoopa","Volcanion","Rowlet","Dartrix","Decidueye","Litten","Torracat","Incineroar","Popplio","Brionne","Primarina","Pikipek","Trumbeak","Toucannon","Yungoos","Gumshoos","Grubbin","Charjabug","Vikavolt","Crabrawler","Crabominable","Oricorio","Cutiefly","Ribombee","Rockruff","Lycanroc","Wishiwashi","Mareanie","Toxapex","Mudbray","Mudsdale","Dewpider","Araquanid","Fomantis","Lurantis","Morelull","Shiinotic","Salandit","Salazzle","Stufful","Bewear","Bounsweet","Steenee","Tsareena","Comfey","Oranguru","Passimian","Wimpod","Golisopod","Sandygast","Palossand","Pyukumuku","Type: Null","Silvally","Minior","Komala","Turtonator","Togedemaru","Mimikyu","Bruxish","Drampa","Dhelmise","Jangmo-o","Hakamo-o","Kommo-o","Tapu Koko","Tapu Lele","Tapu Bulu","Tapu Fini","Cosmog","Cosmoem","Solgaleo","Lunala","Nihilego","Buzzwole","Pheromosa","Xurkitree","Celesteela","Kartana","Guzzlord","Necrozma","Magearna","Marshadow"];
    // german names
    // var pokemon = ["Bisasam","Bisaknosp","Bisaflor","Glumanda","Glutexo","Glurak","Schiggy","Schillok","Turtok","Raupy","Safcon","Smettbo","Hornliu","Kokuna","Bibor","Taubsi","Tauboga","Tauboss","Rattfratz","Rattikarl","Habitak","Ibitak","Rettan","Arbok","Pikachu","Raichu","Sandan","Sandamer","Nidoran♀","Nidorina","Nidoqueen","Nidoran♂","Nidorino","Nidoking","Piepi","Pixi","Vulpix","Vulnona","Pummeluff","Knuddeluff","Zubat","Golbat","Myrapla","Duflor","Giflor","Paras","Parasek","Bluzuk","Omot","Digda","Digdri","Mauzi","Snobilikat","Enton","Entoron","Menki","Rasaff","Fukano","Arkani","Quapsel","Quaputzi","Quappo","Abra","Kadabra","Simsala","Machollo","Maschock","Machomei","Knofensa","Ultrigaria","Sarzenia","Tentacha","Tentoxa","Kleinstein","Georok","Geowaz","Ponita","Gallopa","Flegmon","Lahmus","Magnetilo","Magneton","Porenta","Dodu","Dodri","Jurob","Jugong","Sleima","Sleimok","Muschas","Austos","Nebulak","Alpollo","Gengar","Onix","Traumato","Hypno","Krabby","Kingler","Voltobal","Lektrobal","Owei","Kokowei","Tragosso","Knogga","Kicklee","Nockchan","Schlurp","Smogon","Smogmog","Rihorn","Rizeros","Chaneira","Tangela","Kangama","Seeper","Seemon","Goldini","Golking","Sterndu","Starmie","Pantimos","Sichlor","Rossana","Elektek","Magmar","Pinsir","Tauros","Karpador","Garados","Lapras","Ditto","Evoli","Aquana","Blitza","Flamara","Porygon","Amonitas","Amoroso","Kabuto","Kabutops","Aerodactyl","Relaxo","Arktos","Zapdos","Lavados","Dratini","Dragonir","Dragoran","Mewtu","Mew","Endivie","Lorblatt","Meganie","Feurigel","Igelavar","Tornupto","Karnimani","Tyracroc","Impergator","Wiesor","Wiesenior","Hoothoot","Noctuh","Ledyba","Ledian","Webarak","Ariados","Iksbat","Lampi","Lanturn","Pichu","Pii","Fluffeluff","Togepi","Togetic","Natu","Xatu","Voltilamm","Waaty","Ampharos","Blubella","Marill","Azumarill","Mogelbaum","Quaxo","Hoppspross","Hubelupf","Papungha","Griffel","Sonnkern","Sonnflora","Yanma","Felino","Morlord","Psiana","Nachtara","Kramurx","Laschoking","Traunfugil","Icognito","Woingenau","Girafarig","Tannza","Forstellka","Dummisel","Skorgla","Stahlos","Snubbull","Granbull","Baldorfish","Scherox","Pottrott","Skaraborn","Sniebel","Teddiursa","Ursaring","Schneckmag","Magcargo","Quiekel","Keifel","Corasonn","Remoraid","Octillery","Botogel","Mantax","Panzaeron","Hunduster","Hundemon","Seedraking","Phanpy","Donphan","Porygon2","Damhirplex","Farbeagle","Rabauz","Kapoera","Kussilla","Elekid","Magby","Miltank","Heiteira","Raikou","Entei","Suicune","Larvitar","Pupitar","Despotar","Lugia","Ho-Oh","Celebi","Geckarbor","Reptain","Gewaldro","Flemmli","Jungglut","Lohgock","Hydropi","Moorabbel","Sumpex","Fiffyen","Magnayen","Zigzachs","Geradaks","Waumpel","Schaloko","Papinella","Panekon","Pudox","Loturzel","Lombrero","Kappalores","Samurzel","Blanas","Tengulist","Schwalbini","Schwalboss","Wingull","Pelipper","Trasla","Kirlia","Guardevoir","Gehweiher","Maskeregen","Knilz","Kapilz","Bummelz","Muntier","Letarking","Nincada","Ninjask","Ninjatom","Flurmel","Krakeelo","Krawumms","Makuhita","Hariyama","Azurill","Nasgnet","Eneco","Enekoro","Zobiris","Flunkifer","Stollunior","Stollrak","Stolloss","Meditie","Meditalis","Frizelbliz","Voltenso","Plusle","Minun","Volbeat","Illumise","Roselia","Schluppuck","Schlukwech","Kanivanha","Tohaido","Wailmer","Wailord","Camaub","Camerupt","Qurtel","Spoink","Groink","Pandir","Knacklion","Vibrava","Libelldra","Tuska","Noktuska","Wablu","Altaria","Sengo","Vipitis","Lunastein","Sonnfel","Schmerbe","Welsar","Krebscorps","Krebutack","Puppance","Lepumentas","Liliep","Wielie","Anorith","Armaldo","Barschwa","Milotic","Formeo","Kecleon","Shuppet","Banette","Zwirrlicht","Zwirrklop","Tropius","Palimpalim","Absol","Isso","Schneppke","Firnontor","Seemops","Seejong","Walraisa","Perlu","Aalabyss","Saganabyss","Relicanth","Liebiskus","Kindwurm","Draschel","Brutalanda","Tanhel","Metang","Metagross","Regirock","Regice","Registeel","Latias","Latios","Kyogre","Groudon","Rayquaza","Jirachi","Deoxys","Chelast","Chelcarain","Chelterrar","Panflam","Panpyro","Panferno","Plinfa","Pliprin","Impoleon","Staralili","Staravia","Staraptor","Bidiza","Bidifas","Zirpurze","Zirpeise","Sheinux","Luxio","Luxtra","Knospi","Roserade","Koknodon","Rameidon","Schilterus","Bollterus","Burmy","Burmadame","Moterpel","Wadribie","Honweisel","Pachirisu","Bamelin","Bojelin","Kikugi","Kinoso","Schalellos","Gastrodon","Ambidiffel","Driftlon","Drifzepeli","Haspiror","Schlapor","Traunmagil","Kramshef","Charmian","Shnurgarst","Klingplim","Skunkapuh","Skuntank","Bronzel","Bronzong","Mobai","Pantimimi","Wonneira","Plaudagei","Kryppuk","Kaumalat","Knarksel","Knakrack","Mampfaxo","Riolu","Lucario","Hippopotas","Hippoterus","Pionskora","Piondragi","Glibunkel","Toxiquak","Venuflibis","Finneon","Lumineon","Mantirps","Shnebedeck","Rexblisar","Snibunna","Magnezone","Schlurplek","Rihornior","Tangoloss","Elevoltek","Magbrant","Togekiss","Yanmega","Folipurba","Glaziola","Skorgro","Mamutel","Porygon-Z","Galagladi","Voluminas","Zwirrfinst","Frosdedje","Rotom","Selfe","Vesprit","Tobutz","Dialga","Palkia","Heatran","Regigigas","Giratina","Cresselia","Phione","Manaphy","Darkrai","Shaymin","Arceus","Victini","Serpifeu","Efoserp","Serpiroyal","Floink","Ferkokel","Flambirex","Ottaro","Zwottronin","Admurai","Nagelotz","Kukmarda","Yorkleff","Terribark","Bissbark","Felilou","Kleoparda","Vegimak","Vegichita","Grillmak","Grillchita","Sodamak","Sodachita","Somniam","Somnivora","Dusselgurr","Navitaub","Fasasnob","Elezeba","Zebritz","Kiesling","Sedimantur","Brockoloss","Fleknoil","Fletiamo","Rotomurf","Stalobor","Ohrdoch","Praktibalk","Strepoli","Meistagrif","Schallquap","Mebrana","Branawarz","Jiutesto","Karadonis","Strawickl","Folikon","Matrifol","Toxiped","Rollum","Cerapendra","Waumboll","Elfun","Lilminip","Dressella","Barschuft","Ganovil","Rokkaiman","Rabigator","Flampion","Flampivian","Maracamba","Lithomith","Castellith","Zurrokex","Irokex","Symvolara","Makabaja","Echnatoll","Galapaflos","Karippas","Flapteryx","Aeropteryx","Unratütox","Deponitox","Zorua","Zoroark","Picochilla","Chillabell","Mollimorba","Hypnomorba","Morbitesse","Monozyto","Mitodos","Zytomega","Piccolente","Swaroness","Gelatini","Gelatroppo","Gelatwino","Sesokitz","Kronjuwild","Emolga","Laukaps","Cavalanzas","Tarnpignon","Hutsassa","Quabbel","Apoquallyp","Mamolida","Wattzapf","Voltula","Kastadur","Tentantel","Klikk","Kliklak","Klikdiklak","Zapplardin","Zapplalek","Zapplarang","Pygraulon","Megalon","Lichtel","Laternecto","Skelabra","Milza","Sharfax","Maxax","Petznief","Siberio","Frigometri","Schnuthelm","Hydragil","Flunschlik","Lin-Fu","Wie-Shu","Shardrago","Golbit","Golgantes","Gladiantri","Caesurio","Bisofank","Geronimatz","Washakwil","Skallyk","Grypheldis","Furnifraß","Fermicula","Kapuno","Duodino","Trikephalo","Ignivor","Ramoth","Kobalium","Terrakium","Viridium","Boreos","Voltolos","Reshiram","Zekrom","Demeteros","Kyurem","Keldeo","Meloetta","Genesect","Igamaro","Igastarnish","Brigaron","Fynx","Rutena","Fennexis","Froxy","Amphizel","Quajutsu","Scoppel","Grebbit","Dartiri","Dartignis","Fiaro","Purmel","Puponcho","Vivillon","Leufeo","Pyroleo","Flabébé","Floette","Florges","Mähikel","Chevrumm","Pam-Pam","Pandagro","Coiffwaff","Psiau","Psiaugon","Gramokles","Duokles","Durengard","Parfi","Parfinesse","Flauschling","Sabbaione","Iscalar","Calamanero","Bithora","Thanathora","Algitt","Tandrak","Scampisto","Wummer","Eguana","Elezard","Balgoras","Monargoras","Amarino","Amagarga","Feelinara","Resladero","Dedenne","Rocara","Viscora","Viscargot","Viscogon","Clavion","Paragoni","Trombork","Irrbis","Pumpdjinn","Arktip","Arktilas","eF-eM","UHaFnir","Xerneas","Yveltal","Zygarde","Diancie","Hoopa","Volcanion","Bauz","Arboretoss","Silvarro","Flamiau","Miezunder","Fuegro","Robball","Marikeck","Primarene","Peppeck","Trompeck","Tukanon","Mangunior","Manguspektor","Mabula","Akkup","Donarion","Krabbox","Krawell","Choreogel","Wommel","Bandelby","Wuffels","Wolwerock","Lusardin","Garstella","Aggrostella","Pampuli","Pampross","Araqua","Aranestro","Imantis","Mantidea","Bubungus","Lamellux","Molunk","Amfira","Velursi","Kosturso","Frubberl","Frubaila","Fruyal","Curelei","Kommandutan","Quartermak","Reißlaus","Tectass","Sankabuh","Colossand","Gufa","Typ:Null","Amigento","Meteno","Koalelu","Tortunator","Togedemaru","Mimigma","Knirfish","Sen-Long","Moruda","Miniras","Mediras","Grandiras","Kapu-Riki","Kapu-Fala","Kapu-Toro","Kapu-Kime","Cosmog","Cosmovum","Solgaleo","Lunala","Anego","Masskito","Schabelle","Voltriant","Kaguron","Katagami","Schlingking","Necrozma","Magearna","Marshadow"];
    </script>
<script type="text/javascript">
var debug = false;
//map and control vars
var map;
var manualCircle = false;
var drawControl,
  buttonManualCircle,
  buttonImportNests,
  buttonModalImportPolygon,
  buttonModalImportInstance,
  buttonTrash,
  buttonTrashRoute,
  barShowPolyOpts,
  buttonGenerateRoute,
  buttonOptimizeRoute,
  barRoute,
  buttonModalOutput,
  buttonMapModePoiViewer,
  buttonMapModeRouteGenerator,
  buttonShowGyms,
  buttonShowPokestops,
  buttonShowPokestopsRange,
  buttonShowSpawnpoints,
  buttonShowUnknownPois,
  buttonSettingsModal,
  buttonViewCells;
//data vars
var gyms = [],
  pokestops = [],
  spawnpoints = [];
//options vars
var settings = {
  showGyms: null,
  showPokestops: null,
  showPokestopsRange: null,
  showSpawnpoints: null,
  showUnknownPois: null,
  circleSize: null,
  optimizationAttempts: null,
  nestMigrationDate: null,
  spawnReportLimit: null,
  mapMode: null,
  mapCenter: null,
  mapZoom: null,
  viewCells: null
};
//map layer vars
var gymLayer,
  pokestopLayer,
  pokestopRangeLayer,
  spawnpointLayer,
  editableLayer,
	circleS2Layer,
  circleLayer,
  nestLayer,
  viewCellLayer;
$(function(){
  loadSettings();
  initMap();
  setMapMode();
  setShowMode();

  $('#nestMigrationDate').datetimepicker('sideBySide', true)
  $('#savePolygon').on('click', function(event) {
    var polygonData = [];
    var importReady = true
    //TODO: add error handling
    //TODO: add check for json or txt
    var importType = $("#importPolygonForm input[name=importPolygonDataType]:checked").val()
     if (importType == 'importPolygonDataTypeCoordList') {
      polygonData.push(csvtoarray($('#importPolygonData').val().trim()));
      importReady = true;
    } else if (importType == 'importPolygonDataTypeGeoJson') {
      var geoJson = JSON.parse($('#importPolygonData').val());
      if (geoJson.type == 'FeatureCollection') {
        geoJson.features.forEach(function(feature) {
          if (feature.type == 'Feature' && feature.geometry.type == 'Polygon' && importReady == true) {
            polygonData.push(turf.flip(feature).geometry.coordinates);
            importReady = true;
          } else {
            importReady = false;
          }
        });
      } else {
        if (geoJson.type == 'Feature' && geoJson.geometry.type == 'Polygon') {
          polygonData.push(turf.flip(geoJson).geometry.coordinates);
          importReady = true;
        }
      }
    } else {
      importReady = false;
    }
    if (importReady = true) {
      var polygonOptions = {
        clickable: false,
        color: "#3388ff",
        fill: true,
        fillColor: null,
        fillOpacity: 0.2,
        opacity: 0.5,
        stroke: true,
        weight: 4
      };
      polygonData.forEach(function(polygon) {
        var newPolygon = L.polygon(polygon, polygonOptions).addTo(editableLayer);
      });
    }
    $('#modalImport').modal('hide');
  });
  $('#saveNestPolygon').on('click', function(event) {
    //TODO: add error handling
    //TODO: add check for json or txt
    var importType = $("#importPolygonForm input[name=importPolygonDataType]:checked").val()
    if (importType == 'importPolygonDataTypeGeoJson') {
      geoJson = JSON.parse($('#importPolygonData').val());
      if (geoJson.type == 'FeatureCollection') {
        geoJson.features.forEach(function(feature) {
          feature = turf.flip(feature);
          var polygon = L.polygon(feature.geometry.coordinates, {
            clickable: false,
            color: "#ff8833",
            fill: true,
            fillColor: null,
            fillOpacity: 0.2,
            opacity: 0.5,
            stroke: true,
            weight: 4
          });
          polygon.tags = {};
          polygon.tags.name = feature.properties.name;
          polygon.addTo(nestLayer);
          polygon.bindPopup(function (layer) {
          if (typeof layer.tags.name !== 'undefined') {
            var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Nest: ' + layer.tags.name + '</span></div>';
          }
          var output = name +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Get spawn report</span></div></div>' +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Remove from map</span></div></div>' +
                  '<div class="input-group"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Polygon</span></div></div>';
          return output;
          });
        });
      }
    } 
    $('#modalImport').modal('hide');
  });
  $('#importInstance').on('click', function(event) {
     var name = $("#importInstanceName" ).val();
     var color = $("#instanceColor" ).val();
     getInstance(name,color);
   });

  $('#getOptimizedRoute').on('click', function(event) {
    var optimizeForGyms = $('#optimizeForGyms').parent().hasClass('active');
    var optimizeForPokestops = $('#optimizeForPokestops').parent().hasClass('active');
    var optimizeForSpawnpoints = $('#optimizeForSpawnpoints').parent().hasClass('active');
    var optimizeNests = $('#optimizeNests').parent().hasClass('active');
    var optimizePolygons = $('#optimizePolygons').parent().hasClass('active');
    var optimizeCircles = $('#optimizeCircles').parent().hasClass('active');
    generateOptimizedRoute(optimizeForGyms, optimizeForPokestops, optimizeForSpawnpoints, optimizeNests, optimizePolygons, optimizeCircles);
   });
  $('#modalSpawnReport').on('hidden.bs.modal', function(event) {
    $('#spawnReportTable > tbody').empty();
    $('#spawnReportTableMissed > tbody').empty();
    $('#modalSpawnReport .modal-title').text();
  });
  $('#modalOutput').on('hidden.bs.modal', function(event) {
    $('#outputCircles').val('');
  });
  $('#modalSettings').on('hidden.bs.modal', function(event) {
    var circleSize = $('#circleSize').val();
    var spawnReportLimit = $('#spawnReportLimit').val();
    var optimizationAttempts = $('#optimizationAttempts').val();
    var nestMigrationDate = moment($("#nestMigrationDate").datetimepicker('date')).local().format('X');
    const newSettings = {
      circleSize: circleSize,
      optimizationAttempts: optimizationAttempts,
      nestMigrationDate: nestMigrationDate,
      spawnReportLimit: spawnReportLimit
    };
    Object.keys(newSettings).forEach(function(key) {
      if (settings[key] != newSettings[key]) {
        settings[key] = newSettings[key];
        storeSetting(key);
      }
    });
  });
  $('#cancelSettings').on('click', function(event) {
    processSettings(true);
  });
  $("#selectAllAndCopy").click(function () {
    $(this).parents("#output-body").children("#outputCircles").select();
    document.execCommand('copy');
    $(this).text("Copied!");
  });
})
function initMap() {
  var attrOsm = 'Map data &copy; <a href="https://openstreetmap.org/">OpenStreetMap</a> contributors, Tiles by carto';
  var attrOverpass = 'POI via <a href="https://www.overpass-api.de/">Overpass API</a>';
  var osm = new L.TileLayer(
  // 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png', {
    attribution: [attrOsm, attrOverpass].join(', ')
  });
  map = L.map('map', {
    preferCanvas: true,
    zoomDelta: 0.25,
    zoomSnap: 0.25,
    wheelPxPerZoomLevel: 30}).addLayer(osm).setView(settings.mapCenter, settings.mapZoom);
  circleLayer = new L.FeatureGroup();
  circleLayer.addTo(map);
  editableLayer = new L.FeatureGroup();
  editableLayer.addTo(map);

  circleS2Layer = new L.FeatureGroup();
  circleS2Layer.addTo(map);
  gymLayer = new L.LayerGroup();
  gymLayer.addTo(map);
  pokestopLayer = new L.LayerGroup();
  pokestopLayer.addTo(map);
  pokestopRangeLayer = new L.LayerGroup();
  pokestopRangeLayer.addTo(map);
  spawnpointLayer = new L.LayerGroup();
  spawnpointLayer.addTo(map);
  viewCellLayer = new L.LayerGroup();
  viewCellLayer.addTo(map);
  pokestopCellLayer = new L.LayerGroup();
  pokestopCellLayer.addTo(map);
  spawnpointCellLayer = new L.LayerGroup();
  spawnpointCellLayer.addTo(map);
  nestLayer = new L.LayerGroup();
  nestLayer.addTo(map);
  buttonMapModePoiViewer = L.easyButton({
    id: 'enableMapModePoiViewer',
    states: [{
      stateName: 'enableMapModePoiViewer',
      icon: 'fas fa-binoculars',
      title: 'POI Viewer',
      onClick: function (btn) {
        settings.mapMode = 'PoiViewer';
        storeSetting('mapMode');
        setMapMode();
      }
    }]
  })
  buttonMapModeRouteGenerator = L.easyButton({
    id: 'enableMapModeRouteGenerator',
    states: [{
      stateName: 'enableMapModeRouteGenerator',
      icon: 'fas fa-route',
      title: 'Route Generator',
      onClick: function (btn) {
        settings.mapMode = 'RouteGenerator';
        storeSetting('mapMode');
        setMapMode();
      }
    }]
  })
  var barMapMode = L.easyBar([buttonMapModeRouteGenerator, buttonMapModePoiViewer], { position: 'topright' }).addTo(map);
  buttonShowGyms = L.easyButton({
    id: 'showGyms',
    states: [{
      stateName: 'enableShowGyms',
      icon: 'fas fa-dumbbell',
      title: 'Hide gyms',
      onClick: function (btn) {
        settings.showGyms = false;
        storeSetting('showGyms');
        setShowMode();
        }
    }, {
      stateName: 'disableShowGyms',
      icon: 'fas fa-dumbbell',
      title: 'Show gyms',
      onClick: function (btn) {
        settings.showGyms = true;
        storeSetting('showGyms');
        setShowMode();
      }
    }]
  })
  buttonShowPokestops = L.easyButton({
    id: 'showPokestops',
    states: [{
      stateName: 'enableShowPokestops',
      icon: 'fas fa-map-pin',
      title: 'Hide pokestops',
      onClick: function (btn) {
        settings.showPokestops = false;
        storeSetting('showPokestops');
        setShowMode();
      }
    }, {
      stateName: 'disableShowPokestops',
      icon: 'fas fa-map-pin',
      title: 'Show pokestops',
      onClick: function (btn) {
        settings.showPokestops = true;
        storeSetting('showPokestops');
        setShowMode();
      }
    }]
  })
  buttonShowPokestopsRange = L.easyButton({
    id: 'showPokestopsRange',
    states: [{
      stateName: 'enableShowPokestopsRange',
      icon: 'fas fa-layer-group',
      title: 'Hide pokestop range',
      onClick: function (btn) {
        settings.showPokestopsRange = false;
        storeSetting('showPokestopsRange');
        setShowMode();
      }
    }, {
      stateName: 'disableShowPokestopsRange',
      icon: 'fas fa-layer-group',
      title: 'Show pokestop range',
      onClick: function (btn) {
        settings.showPokestopsRange = true;
        storeSetting('showPokestopsRange');
        setShowMode();
      }
    }]
  })
  buttonShowSpawnpoints = L.easyButton({
    id: 'showSpawnpoints',
    states:[{
      stateName: 'enableShowSpawnpoints',
      icon: 'fas fa-paw',
      title: 'Hide spawnpoints',
      onClick: function (btn) {
        settings.showSpawnpoints = false;
        storeSetting('showSpawnpoints');
        setShowMode();
      }
    }, {
      stateName: 'disableShowSpawnpoints',
      icon: 'fas fa-paw',
      title: 'Show spawnpoints',
      onClick: function (btn) {
        settings.showSpawnpoints = true;
        storeSetting('showSpawnpoints');
        setShowMode();
      }
    }]
  })
  buttonShowUnknownPois = L.easyButton({
    id: 'showUnknownPois',
    states:[{
      stateName: 'enableShowUnknownPois',
      icon: 'fas fa-question-circle',
      title: 'Show all POIs',
      onClick: function (btn) {
        settings.showUnknownPois = false;
        storeSetting('showUnknownPois');
        setShowMode();
      }
    }, {
      stateName: 'disableShowUnknownPois',
      icon: 'fas fa-question-circle',
      title: 'Show unknown POIs only',
      onClick: function (btn) {
        settings.showUnknownPois = true;
        storeSetting('showUnknownPois');
        setShowMode();
      }
    }]
  })
  var barShowPOIs = L.easyBar([buttonShowGyms, buttonShowPokestops, buttonShowPokestopsRange, buttonShowSpawnpoints, buttonShowUnknownPois], { position: 'topright' }).addTo(map);

  buttonViewCells = L.easyButton({
    id: 'viewCells',
		position: 'topright',
    states: [{
      stateName: 'enableViewCells',
      icon: 'far fa-square',
      title: 'Hide viewing cells',
      onClick: function (btn) {
        settings.viewCells = false;
        storeSetting('viewCells');
        setShowMode();
        }
    }, {
      stateName: 'disableViewCells',
      icon: 'far fa-square',
      title: 'Show viewing cells',
      onClick: function (btn) {
        settings.viewCells = true;
        storeSetting('viewCells');
        setShowMode();
      }
    }]
  }).addTo(map)
  drawControl = new L.Control.Draw({
    position: 'topleft',
    draw: {
      polyline: false,
      polygon:   {
        shapeOptions: {
          clickable: false
        }
      },
      circle: false,
      rectangle: false,
      circlemarker: false,
      marker: false
    },
    edit: {
      featureGroup: editableLayer,
      edit: true,
      remove: false,
      poly: false
    }
  }).addTo(map);
  buttonManualCircle = L.easyButton({
    states: [{
      stateName: 'enableManualCircle',
      icon: 'far fa-circle',
      title: 'Enable manual circle mode',
      onClick: function (btn) {
        manualCircle = true;
        btn.state('disableManualCircle');
      }
    }, {
      stateName: 'disableManualCircle',
      icon: 'fas fa-circle',
      title: 'Disable manual circle mode',
      onClick: function (btn) {
        manualCircle = false;
        btn.state('enableManualCircle');
      }
    }]
  });
  buttonImportNests = L.easyButton({
    states: [{
      stateName: 'openImportPolygonModal',
      icon: 'fas fa-tree',
      title: 'Import nests from OSM',
      onClick: function (control){
        getNests();
      }
    }]
  });
  buttonModalImportPolygon = L.easyButton({
    states: [{
      stateName: 'openImportPolygonModal',
      icon: 'fas fa-draw-polygon',
      title: 'Import polygon',
      onClick: function (control){
        $('#modalImportPolygon').modal('show');
      }
    }]
  });
  buttonModalImportInstance = L.easyButton({
    states: [{
      stateName: 'openImportInstanceModal',
      icon: 'fas fa-truck-loading',
      title: 'Import instance',
      onClick: function (control){
        getInstance();
        $('#modalImportInstance').modal('show');
      }
    }]
  });
  buttonTrashRoute = L.easyButton({
    states: [{
      stateName: 'clearMapRoute',
      icon: 'fas fa-times-circle',
      title: 'Clear route',
      onClick: function (control){
        circleLayer.clearLayers();
      }
    }]
  });
  buttonTrash = L.easyButton({
    states: [{
      stateName: 'clearMap',
      icon: 'fas fa-trash',
      title: 'Clear all shapes',
      onClick: function (control){
        circleLayer.clearLayers();
        editableLayer.clearLayers();
        nestLayer.clearLayers();
      }
    }]
  });
  barShowPolyOpts = L.easyBar([buttonManualCircle, buttonImportNests, buttonModalImportPolygon, buttonModalImportInstance, buttonTrashRoute, buttonTrash], { position: 'topleft' }).addTo(map);
  buttonGenerateRoute = L.easyButton({
    id: 'generateRoute',
    states:[{
      stateName: 'generateRoute',
      icon: 'fas fa-cookie',
      title: 'Generate route',
      onClick: function (btn) {
        generateRoute();
      }
    }]
  })
  buttonOptimizeRoute = L.easyButton({
    id: 'optimizeRoute',
    states:[{
      stateName: 'optimizeRoute',
      icon: 'fas fa-cookie-bite',
      title: 'Generate optimized route',
      onClick: function (btn) {
        $('#modalOptimize').modal('show');
      }
    }]
  })
  barRoute = L.easyBar([buttonGenerateRoute, buttonOptimizeRoute], { position: 'topleft' }).addTo(map);
  buttonModalOutput = L.easyButton({
    states: [{
      stateName: 'openOutputModal',
      icon: '<strong>GO</strong>',
      title: 'Get output',
      onClick: function (control){
        $('#modalOutput').modal('show');
      }
    }]
  }).addTo(map);
  buttonModalSettings = L.easyButton({
    position: 'bottomleft',
    states: [{
      stateName: 'openSettingsModal',
      icon: 'fas fa-cog',
      title: 'Open settings',
      onClick: function (control){
        if (settings.circleSize != null) {
          $('#circleSize').val(settings.circleSize);
        } else {
          $('#circleSize').val('500');
        }
        if (settings.spawnReportLimit != null) {
          $('#spawnReportLimit').val(settings.spawnReportLimit);
        } else {
          $('#spawnReportLimit').val('0');
        }
        if (settings.optimizationAttempts != null) {
          $('#optimizationAttempts').val(settings.optimizationAttempts);
        } else {
          $('#optimizationAttempts').val('100');
        }
        if (settings.nestMigrationDate != null) {
          $('#nestMigrationDate').datetimepicker('date', moment.unix(settings.nestMigrationDate).utc().local().format('MM/DD/YYYY HH:mm'));
        }
        $('#modalSettings').modal('show');
      }
    }]
  }).addTo(map);
  map.on('draw:drawstart', function(e) {
    manualCircle = false;
    buttonManualCircle.state('enableManualCircle');
  });

	map.on('draw:created', function (e) {
    var layer = e.layer;
    layer.addTo(editableLayer);
	});

	nestLayer.on('layeradd', function(e) {
		var layer = e.layer;
		layer.bindPopup(function (layer) {
			if (typeof layer.tags.name !== 'undefined') {
				var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Nest: ' + layer.tags.name + '</span></div>';
			}
			var output = name +
			  '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Polygon</span></div>' +
			  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="nestLayer" data-layer-id=' +
			  layer._leaflet_id +
			  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Get spawn report</span></div></div>' +
			  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="nestLayer" data-layer-id=' +
			  layer._leaflet_id +
			  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Remove from map</span></div></div>' +
			  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="nestLayer" data-layer-id=' +
			  layer._leaflet_id +
			  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Polygon</span></div></div>' +
			  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportPoints" data-layer-container="nestLayer" data-layer-id=' +
			  layer._leaflet_id +
			  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Visible Points (CSV)</span></div></div>' +
			  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm countPoints" data-layer-container="nestLayer" data-layer-id=' +
			  layer._leaflet_id +
			  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Count Visible Points</span></div></div>';
			return output;
		});
	});

	editableLayer.on('layeradd', function(e) {
		var layer = e.layer;
		layer.bindPopup(function (layer) {
			var output = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Polygon</span></div>' +
									 '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="editableLayer" data-layer-id=' +
									 layer._leaflet_id +
									 ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Get spawn report</span></div></div>' +
									 '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="editableLayer" data-layer-id=' +
									 layer._leaflet_id +
									 ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Remove from map</span></div></div>' +
									 '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="editableLayer" data-layer-id=' +
									 layer._leaflet_id +
									 ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Polygon</span></div></div>' +
									 '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm exportPoints" data-layer-container="editableLayer" data-layer-id=' +
									 layer._leaflet_id +
									 ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Visible Points (CSV)</span></div></div>' +
									 '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm countPoints" data-layer-container="editableLayer" data-layer-id=' +
									 layer._leaflet_id +
									 ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Count Visible Points</span></div></div>';
			return output;
		});
	});


	circleLayer.on('layerremove', function(e) {
		var layer = e.layer;
		layer.s2cells.forEach(function(item) {
			circleS2Layer.removeLayer(parseInt(item));
		});
	});

circleLayer.on('layeradd', function(e) {
	drawCircleS2Cells(e.layer);
	circleLayer.removeFrom(map).addTo(map);
	e.layer.on('drag', function() {
		drawCircleS2Cells(e.layer)
	})
	e.layer.on('drag', function() {
	circleLayer.removeFrom(map).addTo(map);
	})
});

  map.on('moveend', function() {
    settings.mapCenter = map.getCenter();
    storeSetting('mapCenter');
    settings.mapZoom = map.getZoom();
    storeSetting('mapZoom');
    loadData();
  });
/*   map.on('zoomend', function() {
    settings.mapCenter = map.getCenter();
    storeSetting('mapCenter');
    settings.mapZoom = map.getZoom();
    storeSetting('mapZoom');
    loadData();
  }); */
/*
  map.on('dragend', function() {
    settings.mapCenter = map.getCenter();
    storeSetting('mapCenter');
    settings.mapZoom = map.getZoom();
    storeSetting('mapZoom');
    loadData();
  }); */
  map.on('click', function(e) {
    if (manualCircle === true) {
      var newCircle = new L.circle(e.latlng, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
				draggable: true,
        radius: settings.circleSize
      }).bindPopup(function (layer) {
        return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button></div>';
      }).addTo(circleLayer);
    }
  });
}
function drawCircleS2Cells(layer) {
  if (typeof layer.s2cells !== 'undefined') {
		layer.s2cells.forEach(function(item) {
			circleS2Layer.removeLayer(parseInt(item));
		});
	}
	var center = layer.getLatLng()
	var radius = layer.getRadius();
	layer.s2cells = [];
	function addPoly(cell) {
		const vertices = cell.getCornerLatLngs()
		const poly = L.polygon(vertices,{
			color: 'blue',
			opacity: 0.5,
			weight: 2,
			fillOpacity: 0.0
		});
		var line = turf.polygonToLine(poly.toGeoJSON());
		var point = turf.point([center.lng, center.lat]);
		var distance = turf.pointToLineDistance(point, line, { units: 'meters' });
		if (distance <= radius) {
			circleS2Layer.addLayer(poly);
			layer.s2cells.push(poly._leaflet_id);
		}
	}

	if (radius < 1000 && radius > 200) {
		var count = 10;
		let cell = S2.S2Cell.FromLatLng(layer.getLatLng(), 15)
		let steps = 1
		let direction = 0
		do {
				for (let i = 0; i < 2; i++) {
						for (let i = 0; i < steps; i++) {
								addPoly(cell)
								cell = cell.getNeighbors()[direction % 4]
						}
						direction++
				}
				steps++
		} while (steps < count)
	}
}
function setShowMode() {
  if (settings.showGyms !== false) {
    buttonShowGyms.state('enableShowGyms');
    buttonShowGyms.button.style.backgroundColor = '#B7E9B7';
  } else {
    gymLayer.clearLayers();
    buttonShowGyms.state('disableShowGyms');
    buttonShowGyms.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.showPokestops !== false) {
    buttonShowPokestops.state('enableShowPokestops');
    buttonShowPokestops.button.style.backgroundColor = '#B7E9B7';
  } else {
    pokestopLayer.clearLayers();
    buttonShowPokestops.state('disableShowPokestops');
    buttonShowPokestops.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.showPokestopsRange !== false) {
    buttonShowPokestopsRange.state('enableShowPokestopsRange');
    buttonShowPokestopsRange.button.style.backgroundColor = '#B7E9B7';
  } else {
	pokestopRangeLayer.clearLayers();
    buttonShowPokestopsRange.state('disableShowPokestopsRange');
    buttonShowPokestopsRange.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.showSpawnpoints !== false) {
    buttonShowSpawnpoints.state('enableShowSpawnpoints');
    buttonShowSpawnpoints.button.style.backgroundColor = '#B7E9B7';
  } else {
    spawnpointLayer.clearLayers();
    buttonShowSpawnpoints.state('disableShowSpawnpoints');
    buttonShowSpawnpoints.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.showUnknownPois !== false) {
    buttonShowUnknownPois.state('enableShowUnknownPois');
    buttonShowUnknownPois.button.style.backgroundColor = '#B7E9B7';
  } else {
    buttonShowUnknownPois.state('disableShowUnknownPois');
    buttonShowUnknownPois.button.style.backgroundColor = '#E9B7B7';
  }
  if (settings.viewCells !== false) {
    buttonViewCells.state('enableViewCells');
    buttonViewCells.button.style.backgroundColor = '#B7E9B7';
  } else {
    buttonViewCells.state('disableViewCells');
    buttonViewCells.button.style.backgroundColor = '#E9B7B7';
  }
  loadData();
}
function setMapMode(){
  switch (settings.mapMode) {
    case 'RouteGenerator':
      buttonMapModePoiViewer.button.style.backgroundColor = '#E9B7B7';
      buttonMapModeRouteGenerator.button.style.backgroundColor = '#B7E9B7';
      $('.leaflet-draw').show();
      buttonShowUnknownPois.disable();
      barShowPolyOpts.enable();
      barRoute.enable();
      buttonModalOutput.enable();
      break;
    case 'PoiViewer':
      buttonMapModePoiViewer.button.style.backgroundColor = '#B7E9B7';
      buttonMapModeRouteGenerator.button.style.backgroundColor = '#E9B7B7';
      editableLayer.clearLayers();
      circleLayer.clearLayers();
      nestLayer.clearLayers();
      $('.leaflet-draw').hide();
      buttonShowUnknownPois.enable();
      barShowPolyOpts.disable();
      barRoute.disable();
      buttonModalOutput.disable();
      break;
  }
}
function getInstance(instanceName = null, color = '#1090fa') {
  if (instanceName === null) {
    //get names of all instances
    const data = {
      'get_instance_names': true,
    };
    const json = JSON.stringify(data);

  if (debug !== false) { console.log(json) }

    $.ajax({
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
    if (debug !== false) { console.log(result) }
        var select = $('#importInstanceName');
        select.empty();
        result.forEach(function(item) {
          select.append($("<option>").attr('value',item.name).text(item.name + " (" + item.type + ")"));
        });
      }
    });
  } else {
    //get single instance
    const data = {
      'get_instance_data': true,
      'instance_name': instanceName
    };
    const json = JSON.stringify(data);

		var polygonOptions = {
			clickable: false,
			color: "#3388ff",
			fill: true,
			fillColor: null,
			fillOpacity: 0.2,
			opacity: 0.5,
			stroke: true,
			weight: 4
		};
		if (debug !== false) { console.log(json) }
    $.ajax({
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
				if (debug !== false) { console.log(result) }
				points = result.data.area;
				if (points.length > 0 ) {
					if (result.type == 'circle_pokemon' || result.type == 'circle_raid') {
						points.forEach(function(item) {
							newCircle = L.circle(item, {
								color: color,
								fillOpacity: 0.5,
								draggable: true,
								radius: settings.circleSize
							}).bindPopup(function (layer) {
								return '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button></div>';
							}).addTo(circleLayer);
						});
					} else if (result.type == 'auto_quest' || result.type == 'pokemon_iv') {
						var newPolygon = L.polygon(points, polygonOptions).addTo(editableLayer);
					}
				}
      }
    });
  }
}
function generateOptimizedRoute(optimizeForGyms, optimizeForPokestops, optimizeForSpawnpoints, optimizeNests, optimizePolygons, optimizeCircles) {
  $("#modalLoading").modal('show');

  var newCircle,
    currentLatLng,
    point;

  var pointsOut = [];

  var data = {
    'get_optimization': true,
    'circle_size': settings.circleSize,
    'optimization_attempts': settings.optimizationAttempts,
    'do_tsp': false,
    'points': []
  };
  var routeLayers = function(layer) {
    var points = [];
    var poly = layer.toGeoJSON();
    var line = turf.polygonToLine(poly);
    if (optimizeForGyms == true) {
      gyms.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if (optimizeForPokestops == true) {
      pokestops.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if (optimizeForSpawnpoints == true) {
      spawnpoints.forEach(function(item) {
        point = turf.point([item.lng, item.lat]);
        if (turf.inside(point, poly)) {
          points.push(item)
        }
      });
    }
    if(points.length > 0) {
      getRoute(points);
    }
  }

  var routeCircles = function(layer) {
    var points = []
    var radius = layer.getRadius();
    var bounds = layer.getBounds();
    var center = bounds.getCenter();
    if (optimizeForGyms == true) {
      gyms.forEach(function(item) {
        var workingLatLng = L.latLng(item.lat, item.lng);
        var distance = workingLatLng.distanceTo(center)
        if (distance <= radius) {
          points.push(item);
        }
      });
    }
    if (optimizeForPokestops == true) {
      pokestops.forEach(function(item) {
        var workingLatLng = L.latLng(item.lat, item.lng);
        var distance = workingLatLng.distanceTo(center)
        if (distance <= radius) {
          points.push(item);
        }
      });
    }
    if (optimizeForSpawnpoints == true) {
      spawnpoints.forEach(function(item) {
        var workingLatLng = L.latLng(item.lat, item.lng);
        var distance = workingLatLng.distanceTo(center)
        if (distance <= radius) {
          points.push(item);
        }
      });
    }
    if(points.length > 0) {
      return points;
    }
  }

  var getRoute = function(points) {
    data.points = _.uniq(points);
    const json = JSON.stringify(data);
    if (debug !== false) { console.log(data) }
    console.log(json)
    $.ajax({
      beforeSend: function() {
      },
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
        if (debug !== false) { console.log(result) }
          result.bestAttempt.forEach(function(point) {
           newCircle = L.circle([point.lat, point.lng], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
						draggable: true,
            radius: settings.circleSize
          }).bindPopup(function (layer) {
            return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button></div>';
          }).addTo(circleLayer);
        });
      },
      complete: function() { }
    });
  }

  if (optimizePolygons == true) {
    editableLayer.eachLayer(function (layer) {
       routeLayers(layer);
    });
  }
  if (optimizeNests == true) {
    nestLayer.eachLayer(function (layer) {
       routeLayers(layer);
    });
  }

  if (optimizeCircles == true) {
    circleLayer.eachLayer(function (layer) {
      pointsOut = pointsOut.concat(routeCircles(layer));
      circleLayer.removeLayer(layer);
    });
    getRoute(pointsOut);
  }
  $("#modalLoading").modal('hide');
}
function generateRoute() {
  circleLayer.clearLayers();
  var xMod = Math.sqrt(0.75);
  var yMod = Math.sqrt(0.568);
  var route = function(layer) {
    var poly = layer.toGeoJSON();
    var line = turf.polygonToLine(poly);
    var newCircle;
    var currentLatLng = layer.getBounds().getNorthEast();
    var startLatLng = L.GeometryUtil.destination(currentLatLng, 90, settings.circleSize*1.5);
    var endLatLng = L.GeometryUtil.destination(L.GeometryUtil.destination(layer.getBounds().getSouthWest(), 270, settings.circleSize*1.5), 180, settings.circleSize);
    var row = 0;
    var heading = 270;
    var i = 0;
    while(currentLatLng.lat > endLatLng.lat) {
      do {
        var point = turf.point([currentLatLng.lng, currentLatLng.lat]);
        var distance = turf.pointToLineDistance(point, line, { units: 'meters' });
        if (distance <= settings.circleSize || distance == 0 || turf.inside(point, poly)) {
          newCircle = L.circle(currentLatLng, {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
						draggable: true,
            radius: settings.circleSize
          }).bindPopup(function (layer) {
            return '<button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="circleLayer" data-layer-id=' + layer._leaflet_id + ' type="button">Delete</button></div>';
          }).addTo(circleLayer);
        }
        currentLatLng = L.GeometryUtil.destination(currentLatLng, heading, (xMod*settings.circleSize*2));
        i++;
      }while((heading == 270 && currentLatLng.lng > endLatLng.lng) || (heading == 90 && currentLatLng.lng < startLatLng.lng));
      currentLatLng = L.GeometryUtil.destination(currentLatLng, 180, (yMod*settings.circleSize*2));
      rem = row%2;
      if (rem == 1) {
        heading = 270;
      } else {
        heading = 90;
      }
      currentLatLng = L.GeometryUtil.destination(currentLatLng, heading, (xMod*settings.circleSize)*3);
      row++;
    }
  }
  editableLayer.eachLayer(function (layer) {
     route(layer);
  });
  nestLayer.eachLayer(function (layer) {
     route(layer);
  });
}
function getSpawnReport(layer) {
  var reportStops = [],
    reportSpawns = [];
  var poly = layer.toGeoJSON();
  var line = turf.polygonToLine(poly);
  pokestops.forEach(function(item) {
    point = turf.point([item.lng, item.lat]);
    if (turf.inside(point, poly)) {
      reportStops.push(item.id);
    }
  });
  spawnpoints.forEach(function(item) {
    point = turf.point([item.lng, item.lat]);
    if (turf.inside(point, poly)) {
      reportSpawns.push(item.id);
    }
  });
  const data = {
    'get_spawndata': true,
    'nest_migration_timestamp': settings.nestMigrationDate,
    'spawn_report_limit': settings.spawnReportLimit,
    'stops': reportStops,
    'spawns': reportSpawns
  };
  const json = JSON.stringify(data);
  if (debug !== false) { console.log(json) }

  $.ajax({
    beforeSend: function() {
      $("#modalLoading").modal('show');
    },
    url: this.href,
    type: 'POST',
    dataType: 'json',
    data: {'data': json},
    success: function (result) {
      console.log(result)
      if (debug !== false) { console.log(result) }
      if (result.spawns !== null) {
        result.spawns.forEach(function(item) {
          if (typeof layer.tags !== 'undefined') {
            $('#modalSpawnReport  .modal-title').text('Spawn Report - ' + layer.tags.name);
          }
          $('#spawnReportTable > tbody:last-child').append('<tr><td>' +pokemon[item.pokemon_id-1] + '</td><td>' + item.count + '</td></tr>');
        });
      } else {
          if (typeof layer.tags !== 'undefined') {
          $('#modalSpawnReport  .modal-title').text('Spawn Report - ' + layer.tags.name);
        }
        $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2">No data available.</td></tr>');
      }
    },
    complete: function() {
      $("#modalLoading").modal('hide');
      $('#modalSpawnReport').modal('show');
    }
  });
}
function getNests() {
  circleLayer.clearLayers();
  editableLayer.clearLayers();
  nestLayer.clearLayers();
  const bounds = map.getBounds();
  const overpassApiEndpoint = 'https://overpass-api.de/api/interpreter';
  var queryBbox = [ // s, e, n, w
    bounds.getSouthWest().lat,
    bounds.getSouthWest().lng,
    bounds.getNorthEast().lat,
    bounds.getNorthEast().lng
  ].join(',');
  var queryDate = "2019-01-22T00:00:00Z";
  var queryOptions = [
    '[out:json]',
    '[bbox:' + queryBbox + ']',
    '[date:"' + queryDate + '"]'
  ].join('');
  var queryNestWays = [
    'way["leisure"="park"];',
    'way["leisure"="recreation_ground"];',
    'way["landuse"="recreation_ground"];',
	'way[leisure=playground];',
    'way[landuse=meadow];'
  ].join('');
  var overPassQuery = queryOptions + ';(' + queryNestWays + ')' + ';out;>;out skel qt;';
  if (debug !== false) { console.log(overPassQuery) }

  $.ajax({
    beforeSend: function() {
      $("#modalLoading").modal('show');
    },
    url: overpassApiEndpoint,
    type: 'GET',
    dataType: 'json',
    data: {'data': overPassQuery},
    success: function (result) {
      if (debug !== false) { console.log(result) }
      var geoJsonFeatures = osmtogeojson(result);
      geoJsonFeatures.features.forEach(function(feature) {
        feature = turf.flip(feature);
        var polygon = L.polygon(feature.geometry.coordinates, {
          clickable: false,
          color: "#ff8833",
          fill: true,
          fillColor: null,
          fillOpacity: 0.2,
          opacity: 0.5,
          stroke: true,
          weight: 4
        });
        polygon.tags = {};
        polygon.tags.name = feature.properties.tags.name;
        polygon.addTo(nestLayer);
        polygon.bindPopup(function (layer) {
          if (typeof layer.tags.name !== 'undefined') {
            var name = '<div class="input-group mb-3 nestName"><span style="padding: .375rem .75rem; width: 100%">Nest: ' + layer.tags.name + '</span></div>';
          }
          var output = name +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm getSpawnReport" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Get spawn report</span></div></div>' +
                  '<div class="input-group mb-3"><button class="btn btn-secondary btn-sm deleteLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Remove from map</span></div></div>' +
                  '<div class="input-group"><button class="btn btn-secondary btn-sm exportLayer" data-layer-container="nestLayer" data-layer-id=' +
                  layer._leaflet_id +
                  ' type="button">Go!</button><div class="input-group-append"><span style="padding: .375rem .75rem;">Export Polygon</span></div></div>';
          return output;
        });
      });
    },
    complete: function() {
      $("#modalLoading").modal('hide');
    }
  });
}
function csvtoarray(dataString) {
  var lines = dataString
    .split(/\n/)           // Convert to one string per line
    .map(function(lineStr) {
      return lineStr.split(",");   // Convert each line to array (,)
    })
  return lines;
}
function loadData() {
  const bounds = map.getBounds();
  const data = {
    'get_data': true,
    'min_lat': bounds.getSouthWest().lat,
    'max_lat': bounds.getNorthEast().lat,
    'min_lng': bounds.getSouthWest().lng,
    'max_lng': bounds.getNorthEast().lng,
    'show_gyms': true,
    'show_pokestops': true,
    'show_spawnpoints': true,
    'show_unknownpois': settings.showUnknownPois
  };
  const json = JSON.stringify(data);

  if (debug !== false) { console.log(json) }

  $.ajax({
    url: this.href,
    type: 'POST',
    dataType: 'json',
    data: {'data': json},
    success: function (result) {
      if (debug !== false) { console.log(result) }
      pokestopLayer.clearLayers();
	    pokestopRangeLayer.clearLayers();
      gymLayer.clearLayers();
      spawnpointLayer.clearLayers();
      gyms = [];
      pokestops = [];
      spawnpoints = [];
      if (result.gyms != null) {
        result.gyms.forEach(function(item) {
          gyms.push(item);
          if (settings.showGyms === true) {
            if(item.ex == 1){
              var marker = L.circleMarker([item.lat, item.lng], {
              color: 'maroon',
              radius: 2,
              opacity: 0.6
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "<br>" + item.name + "</span>").addTo(gymLayer);
            }
            else{
				var marker = L.circleMarker([item.lat, item.lng], {
              color: 'red',
              radius: 2,
              opacity: 0.6
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "<br>" + item.name + "</span>").addTo(gymLayer);
			}
          }
        });
      }
      if (result.pokestops != null) {
        result.pokestops.forEach(function(item) {
          pokestops.push(item);
          if (settings.showPokestops === true) {
            var marker = L.circleMarker([item.lat, item.lng], {
              color: 'green',
              radius: 2,
              opacity: 0.6
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "<br>" + item.name + "</span>").addTo(pokestopLayer);
          }
        });
      }
	  if (result.pokestops != null) {
        result.pokestops.forEach(function(item) {
          pokestops.push(item);
          if (settings.showPokestopsRange === true) {
            var marker = L.circle([item.lat, item.lng], {
              color: 'green',
              radius: 70,
              opacity: 0.2
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "</span>").addTo(pokestopRangeLayer);
          }
        });
      }
      if (result.spawnpoints != null) {
        result.spawnpoints.forEach(function(item) {
          spawnpoints.push(item);
          if (settings.showSpawnpoints === true){
            if (item.despawn_sec != null){
            var marker = L.circleMarker([item.lat, item.lng], {
              color: 'blue',
              radius: 1,
              opacity: 0.6
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            var despawn_minute = Math.floor(item.despawn_sec/60);
            var despawn_second = item.despawn_sec%60;
            marker.bindPopup("<span>ID: " + item.id + "</span>" + " Despawn time: hh:" + despawn_minute +":"+ despawn_second).addTo(spawnpointLayer);
            }
            else{
            var marker = L.circleMarker([item.lat, item.lng], {
              color: 'red',
              radius: 1,
              opacity: 0.6
            }).addTo(map);
            marker.tags = {};
            marker.tags.id = item.id;
            marker.bindPopup("<span>ID: " + item.id + "</span>" + " despawn_sec: unknown").addTo(spawnpointLayer);
          }
        }});
      }
    }
  });

  updateS2Overlay()
}
$(document).ready(function() {
  $('input[type=radio][name=exportPolygonDataType]').change(function() {
    if (this.value == 'exportPolygonDataTypeCoordsList') {
      $('#exportPolygonDataCoordsList').show();
      $('#exportPolygonDataGeoJson').hide();
    }
    else if (this.value == 'exportPolygonDataTypeGeoJson') {
      $('#exportPolygonDataCoordsList').hide();
      $('#exportPolygonDataGeoJson').show();
    }
  });
  $('#getOutput').click(function() {
    $('#outputCircles').val('');
    var allCircles = circleLayer.getLayers();
    for (i=0;i<allCircles.length;i++) {
      var circleLatLng = allCircles[i].getLatLng();
      $('#outputCircles').val(function(index, text) {
        if (i != allCircles.length-1) {
          return text + (circleLatLng.lat + "," + circleLatLng.lng) + "\n" ;
        }
        return text + (circleLatLng.lat + "," + circleLatLng.lng);
      });
    }
  });
});
$(document).on("click", ".deleteLayer", function() {
  var id = $(this).attr('data-layer-id');
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'circleLayer':
      circleLayer.removeLayer(parseInt(id));
      break;
    case 'editableLayer':
      editableLayer.removeLayer(parseInt(id));
      break;
    case 'nestLayer':
      nestLayer.removeLayer(parseInt(id));
      break;
  }
});
$(document).on("click", ".getSpawnReport", function() {
  var id = $(this).attr('data-layer-id');
  var layer;
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'editableLayer':
      layer = editableLayer.getLayer(parseInt(id));
      break;
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }
  getSpawnReport(layer);
});
$(document).on("click", "#getAllNests", function() {
  var spawnReportLimit = $('#spawnReportLimit').val();
  var nestMigrationDate = moment($("#nestMigrationDate").datetimepicker('date')).local().format('X');
  const newSettings = {
    nestMigrationDate: nestMigrationDate,
    spawnReportLimit: spawnReportLimit
  };
  Object.keys(newSettings).forEach(function(key) {
    if (settings[key] != newSettings[key]) {
      settings[key] = newSettings[key];
      storeSetting(key);
    }
  });

  var missedCount = 0;
  nestLayer.eachLayer(function(layer) {
    var reportStops = [],
      reportSpawns = [];
    var center = layer.getBounds().getCenter()
    var poly = layer.toGeoJSON();
    var line = turf.polygonToLine(poly);
    pokestops.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        reportStops.push(item.id);
      }
    });
    spawnpoints.forEach(function(item) {
      point = turf.point([item.lng, item.lat]);
      if (turf.inside(point, poly)) {
        reportSpawns.push(item.id);
      }
    });
    const data = {
      'get_spawndata': true,
      'nest_migration_timestamp': settings.nestMigrationDate,
      'spawn_report_limit': settings.spawnReportLimit,
      'stops': reportStops,
      'spawns': reportSpawns
    };
    const json = JSON.stringify(data);
    if (debug !== false) { console.log(json) }
    $.ajax({
      beforeSend: function() {
        $("#modalLoading").modal('show');
      },
      url: this.href,
      type: 'POST',
      dataType: 'json',
      data: {'data': json},
      success: function (result) {
        if (debug !== false) { console.log(result) }
        if (result.spawns !== null) {
          if (typeof layer.tags.name !== 'undefined') {
            $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2"><strong>Spawn Report - ' + layer.tags.name + '</strong> <em style="font-size:xx-small">at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td></tr>');
          } else {
            $('#spawnReportTable > tbody:last-child').append('<tr><td colspan="2"><strong>Spawn Report - Unnamed</strong> at <em style="font-size:xx-small">' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + '</em></td></tr>');
          }
          result.spawns.forEach(function(item) {
            $('#spawnReportTable > tbody:last-child').append('<tr><td>' +pokemon[item.pokemon_id-1] + '</td><td>' + item.count + '</td></tr>');
          });
        } else {
          if (typeof layer.tags.name !== 'undefined') {
            $('#spawnReportTableMissed > tbody:last-child').append('<tr><td colspan="2"><em style="font-size:xx-small"><strong>' + layer.tags.name + '</strong>  at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + ' skipped, no data</em></td></tr>');
          } else {
            $('#spawnReportTableMissed > tbody:last-child').append('<tr><td colspan="2"><em style="font-size:xx-small"><strong>Unnamed</strong> at ' + center.lat.toFixed(4) + ', ' + center.lng.toFixed(4) + ' skipped, no data</em></td></tr>');
          }
        }
      },
      complete: function() {
        $("#modalLoading").modal('hide');
        $('#modalSpawnReport  .modal-title').text('Nest Report - All Nests in View');
        $('#modalSettings').modal('hide');
        $('#modalSpawnReport').modal('show');
      }
    });
  });
});
$(document).on("click", ".exportLayer", function() {
  var id = $(this).attr('data-layer-id');
  var layer;
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'editableLayer':
      layer = editableLayer.getLayer(parseInt(id));
      break;
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }
  var polyjson = JSON.stringify(layer.toGeoJSON());
  $('#exportPolygonDataGeoJson').val(polyjson);

  var polycoords = '';
  turf.flip(layer.toGeoJSON()).geometry.coordinates[0].forEach(function(item) {
    polycoords += item[0] + ',' + item[1] + "\n";
  });

  $('#exportPolygonDataCoordsList').val(polycoords);
  $('#exportPolygonDataGeoJson').hide();
  $('#modalExportPolygon').modal('show');
});
$(document).on("click", ".exportPoints", function() {
  var id = $(this).attr('data-layer-id');
  var layer;
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'editableLayer':
      layer = editableLayer.getLayer(parseInt(id));
      break;
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }
	var poly = layer.toGeoJSON();
	var line = turf.polygonToLine(poly);
  var gymcoords = '';
  var stopcoords = '';
  var spawncoords = '';
	if (settings.showGyms == true) {
		gyms.forEach(function(item) {
			point = turf.point([item.lng, item.lat]);
			if (turf.inside(point, poly)) {
				gymcoords += item.lat + ',' + item.lng + "\n";
			}
		});
	}
	if (settings.showPokestops == true) {
		pokestops.forEach(function(item) {
			point = turf.point([item.lng, item.lat]);
			if (turf.inside(point, poly)) {
				stopcoords += item.lat + ',' + item.lng + "\n";
			}
		});
	}
	if (settings.showSpawnpoints == true) {
		spawnpoints.forEach(function(item) {
			point = turf.point([item.lng, item.lat]);
			if (turf.inside(point, poly)) {
				spawncoords += item.lat + ',' + item.lng + "\n";
			}
		});
	}


  $('#exportPolygonPointsGyms').val('');
  $('#exportPolygonPointsPokestops').val('');
  $('#exportPolygonPointsSpawnpoints').val('');
  $('#exportPolygonPointsGyms').val(gymcoords);
  $('#exportPolygonPointsPokestops').val(stopcoords);
  $('#exportPolygonPointsSpawnpoints').val(spawncoords);
  $('#modalExportPolygonPoints').modal('show');
});
$(document).on("click", ".countPoints", function() {
  var id = $(this).attr('data-layer-id');
  var layer;
  var container = $(this).attr('data-layer-container');
  switch (container) {
    case 'editableLayer':
      layer = editableLayer.getLayer(parseInt(id));
      break;
    case 'nestLayer':
      layer = nestLayer.getLayer(parseInt(id));
      break;
  }
	var points = 0;
	var poly = layer.toGeoJSON();
	var line = turf.polygonToLine(poly);
	if (settings.showGyms == true) {
		gyms.forEach(function(item) {
			point = turf.point([item.lng, item.lat]);
			if (turf.inside(point, poly)) {
				points++;
			}
		});
	}
	if (settings.showPokestops == true) {
		pokestops.forEach(function(item) {
			point = turf.point([item.lng, item.lat]);
			if (turf.inside(point, poly)) {
				points++;
			}
		});
	}
	if (settings.showSpawnoints == true) {
		spawnpoints.forEach(function(item) {
			point = turf.point([item.lng, item.lat]);
			if (turf.inside(point, poly)) {
				points++;
			}
		});
	}
	alert('Count: ' + points);
});
function loadSettings() {
   const defaultSettings = {
    showGyms: true,
    showPokestops: false,
	  showPokestopsRange: false,
    showSpawnpoints: false,
    showUnknownPois: false,
    circleSize: 500,
    optimizationAttempts: 10,
    nestMigrationDate: 1539201600,
    spawnReportLimit: 10,
    mapMode: 'PoiViewer',
    mapCenter: [42.548197, -83.14684],
    mapZoom: 13,
    viewCells: false
  }
  Object.keys(settings).forEach(function(key) {
    storedSetting = retrieveSetting(key);
    if (storedSetting !== null) {
      settings[key] = storedSetting;
    } else {
      settings[key] = defaultSettings[key];
      storeSetting(key)
    }
  });
}
function storeSetting(key) {
  localStorage.setItem(key, JSON.stringify(settings[key]));
}
function retrieveSetting(key) {
  var value;
  if (localStorage.getItem(key) !== 'undefined') {
    value = JSON.parse(localStorage.getItem(key));
  } else {
    value = null;
  }
  return value;
}
function showS2Cells(level, style) {
  // Credit goes to the PMSF project
  const bounds = map.getBounds()
  const size = L.CRS.Earth.distance(bounds.getSouthWest(), bounds.getNorthEast()) / 4000 + 1 | 0
  const count = 2 ** level * size >> 11
  function addPoly(cell) {
    const vertices = cell.getCornerLatLngs()
    const poly = L.polygon(vertices,
      Object.assign({color: 'orange', opacity: 0.5, weight: 1, fillOpacity: 0.0}, style))
    if (cell.level === 15) {
      viewCellLayer.addLayer(poly)
    }
  }
  // add cells spiraling outward
  let cell = S2.S2Cell.FromLatLng(bounds.getCenter(), level)
  let steps = 1
  let direction = 0
  do {
    for (let i = 0; i < 2; i++) {
      for (let i = 0; i < steps; i++) {
        //if (bounds.contains(cell.getLatLng())) {
          addPoly(cell)
        //}
        cell = cell.getNeighbors()[direction % 4]
      }
      direction++
    }
    steps++
  } while (steps < count)
}
function updateS2Overlay() {
		if (settings.viewCells && (map.getZoom() >= 13.5)) {
				viewCellLayer.clearLayers()
				showS2Cells(15, {color: 'DarkOrange', weight: 2})
				editableLayer.removeFrom(map).addTo(map);
				nestLayer.removeFrom(map).addTo(map);
				circleLayer.removeFrom(map).addTo(map);
		} else if (settings.viewCells && (map.getZoom() < 13.5)) {
				viewCellLayer.clearLayers()
				console.log('View cells are currently hidden, zoom in')
		} else {
				viewCellLayer.clearLayers()
		}
}
</script>
  </head>
  <body>
    <div id="map"></div>

    <div class="modal" id="modalSettings" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Settings</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Route Optimization Attempts:</span>
              </div>
              <input id="optimizationAttempts" name="optimizationAttempts" type="text" class="form-control" aria-label="Optimization attempts">
              <div class="input-group-append">
                <span class="input-group-text">Tries</span>
              </div>
            </div>

            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Circle Radius:</span>
              </div>
              <input id="circleSize" name="circleSize" type="text" class="form-control" aria-label="Circle Radius (in meters)">
              <div class="input-group-append">
                <span class="input-group-text">Meters</span>
              </div>
            </div>

            <div class="input-group mb-3 date" id="nestMigrationDate" data-target-input="nearest">
              <div class="input-group-prepend">
                <span class="input-group-text">Last Nest Migration:</span>
              </div>
              <input type="text" class="form-control datetimepicker-input" data-target="#nestMigrationDate"/>
              <div class="input-group-append" data-target="#nestMigrationDate" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>


            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Spawn Report Limit:</span>
              </div>
              <input id="spawnReportLimit" name="spawnReportLimit" type="text" class="form-control" aria-label="Spawn report limit">
              <div class="input-group-append">
                <span class="input-group-text">Pokemon (0 for unlimited)</span>
              </div>
            </div>

            <div class="btn-toolbar">
              <div class="btn-group" role="group" aria-label="">
                <button id="getAllNests" class="btn btn-primary float-left" type="button">Get all nest reports</button>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" id="saveSettings" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalOutput" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Output</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="mapMode">Generated route:</label>
            <div class="input-group mb-3">
              <textarea id="outputCircles" style="height:400px;" class="form-control" aria-label="Route output"></textarea>
            </div>
            <div class="btn-toolbar">
              <div class="btn-group mr-2" role="group" aria-label="">
                <button id="getOutput" class="btn btn-primary float-left" type="button">Get output</button>
              </div>
              <div class="btn-group" role="group" aria-label="">
                <button id="selectAllAndCopy" class="btn btn-secondary float-right" type="button">Copy to clipboard</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalImportInstance" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Import Instance</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="importInstanceName">Select an instance:</label>
            <div class="input-group mb-3">
              <select name="importInstanceName" id="importInstanceName" class="form-control" aria-label="Select an instance to import">
              </select>
            </div>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Instance Color (hex):</span>
              </div>
              <input id="instanceColor" name="instanceColor" type="text" class="form-control" value="#1090fa" aria-label="Hexadecimal Color for Imported Instance"/>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="importInstance" class="btn btn-primary" data-dismiss="modal">Import Instance</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal" id="modalImportPolygon" tabindex="-1" role="dialog">
      <form id="importPolygonForm">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Import Polygon</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <label for="importPolygonDataType">Polygon data type:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="importPolygonDataType" id="importPolygonDataTypeCoordList" value="importPolygonDataTypeCoordList" checked>
                <label class="form-check-label" for="importPolygonDataTypeCoordList">
                  Coordinate list (latitude,longitude on each new line)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="importPolygonDataType" id="importPolygonDataTypeGeoJson" value="importPolygonDataTypeGeoJson">
                <label class="form-check-label" for="importPolygonDataTypeGeoJson">
                  GeoJSON
                </label>
              </div>
              <div class="form-check disabled">
                <input class="form-check-input" type="radio" name="importPolygonDataType" id="importPolygonDataTypeKmlGeo" value="importPolygonDataTypeKmlGeo" disabled>
                <label class="form-check-label" for="importPolygonDataTypeKmlGeo">
                  KML Geo (not yet implented)
                </label>
              </div>
              <label for="importPolygonData">Polygon data:</label>
              <div class="input-group mb">
                <textarea name="importPolygonData" id="importPolygonData" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="savePolygon" class="btn btn-primary">Import</button>
              <button type="button" id="saveNestPolygon" class="btn btn-secondary">Import as nest</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="modal" id="modalExportPolygon" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Export Polygon</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <label for="exportPolygonDataType">Polygon data type:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exportPolygonDataType" id="exportPolygonDataTypeCoordsList" value="exportPolygonDataTypeCoordsList" checked>
                <label class="form-check-label" for="exportPolygonDataTypeCoordsList">
                  Coordinate list (latitude,longitude on each new line)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exportPolygonDataType" id="exportPolygonDataTypeGeoJson" value="exportPolygonDataTypeGeoJson">
                <label class="form-check-label" for="exportPolygonDataTypeGeoJson">
                  GeoJSON
                </label>
              </div>
            <label for="exportPolygonData">Polygon data:</label>
            <div class="input-group mb">
              <textarea name="exportPolygonDataGeoJson" id="exportPolygonDataGeoJson" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
            </div>
            <div class="input-group mb">
              <textarea name="exportPolygonDataCoords" id="exportPolygonDataCoordsList" style="height:400px;" class="form-control" aria-label="Polygon data"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="exportPolygonClose" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalExportPolygonPoints" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Export Polygon Points (CSV)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="exportPolygonData">Gym points:</label>
            <div class="input-group mb">
              <textarea name="exportPolygonPointsGyms" id="exportPolygonPointsGyms" style="height:200px;" class="form-control" aria-label="Gym data"></textarea>
            </div>
            <label for="exportPolygonData">Pokestop points:</label>
            <div class="input-group mb">
              <textarea name="exportPolygonPointsPokestops" id="exportPolygonPointsPokestops" style="height:200px;" class="form-control" aria-label="Pokestop data"></textarea>
            </div>
            <label for="exportPolygonData">Spawnpoint points:</label>
            <div class="input-group mb">
              <textarea name="exportPolygonPointsPokestops" id="exportPolygonPointsSpawnpoints" style="height:200px;" class="form-control" aria-label="Spawnpoint data"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="exportPolygonPointsClose" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalOptimize" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Optimize!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active">
                  <input type="radio" name="optimizeForGyms" id="optimizeForGyms" autocomplete="off"> On
                </label>
                <label class="btn btn-secondary">
                  <input type="radio" name="optimizeForGyms" id="dontOptimizeForGyms" autocomplete="off"> Off
                </label>
              </div>
              <div class="input-group-append">
                <span style="padding: .375rem .75rem;">Optimize for known gyms</span>
              </div>
            </div>
            <div class="input-group mb-3">
              <div class="btn-group btn-group-toggle"data-toggle="buttons">
                <label class="btn btn-secondary active">
                  <input type="radio" name="optimizeForPokestops" id="optimizeForPokestops" autocomplete="off"> On
                </label>
                <label class="btn btn-secondary">
                  <input type="radio" name="optimizeForPokestops" id="dontOptimizeForPokestops" autocomplete="off"> Off
                </label>
              </div>
              <div class="input-group-append" width>
                <span style="padding: .375rem .75rem;">Optimize for known pokestops</span>
              </div>
            </div>
            <div class="input-group mb-3">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active">
                  <input type="radio" name="optimizeForSpawnpoints" id="optimizeForSpawnpoints" autocomplete="off"> On
                </label>
                <label class="btn btn-secondary">
                  <input type="radio" name="optimizeForSpawnpoints" id="dontOptimizeForSpawnpoints" autocomplete="off"> Off
                </label>
              </div>
              <div class="input-group-append">
                <span style="padding: .375rem .75rem;">Optimize for known spawn points</span>
              </div>
            </div>

            <div class="input-group mb-3">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active">
                  <input type="radio" name="optimizePolygons" id="optimizePolygons" autocomplete="off"> On
                </label>
                <label class="btn btn-secondary">
                  <input type="radio" name="optimizePolygons" id="dontOptimizePolygons" autocomplete="off"> Off
                </label>
              </div>
              <div class="input-group-append">
                <span style="padding: .375rem .75rem;">Optimize points in polygons</span>
              </div>
            </div>
            <div class="input-group mb-3">
              <div class="btn-group btn-group-toggle"data-toggle="buttons">
                <label class="btn btn-secondary active">
                  <input type="radio" name="optimizeNests" id="optimizeNests" autocomplete="off"> On
                </label>
                <label class="btn btn-secondary">
                  <input type="radio" name="optimizeNests" id="dontOptimizeNests" autocomplete="off"> Off
                </label>
              </div>
              <div class="input-group-append" width>
                <span style="padding: .375rem .75rem;">Optimize points in nest polygons</span>
              </div>
            </div>
            <div class="input-group mb-3">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active">
                  <input type="radio" name="optimizeCircles" id="optimizeCircles" autocomplete="off"> On
                </label>
                <label class="btn btn-secondary">
                  <input type="radio" name="optimizeCircles" id="dontOptimizeCircles" autocomplete="off"> Off
                </label>
              </div>
              <div class="input-group-append">
                <span style="padding: .375rem .75rem;">Optimize points in circles</span>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" id="getOptimizedRoute" class="btn btn-primary" data-dismiss="modal">Get Optimization</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="modalSpawnReport" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-sm" id="spawnReportTable">
              <thead>
                <tr>
                  <th scope="col">Pokemon</th>
                  <th scope="col">Count</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <table class="table table-sm" id="spawnReportTableMissed">
              <tbody>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal modal-loader" id="modalLoading" data-backdrop="static" data-keyboard="false" tabindex="-1">
      <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 48px">
          <span class="fa fa-spinner fa-spin fa-3x"></span>
        </div>
      </div>
    </div>
  </body>
</html><?php
}
function initDB($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME, $DB_PORT) {

  $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;port=$DB_PORT;charset=utf8mb4";
  $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => true,
  ];
  $pdo = new PDO($dsn, $DB_USER, $DB_PSWD, $options);
  return $pdo;
}
function map_helper_init() {
  global $db;
  $db = initDB(DB_HOST, DB_USER, DB_PSWD, DB_NAME, DB_PORT);
  $args = json_decode($_POST['data']);
  if (isset($args->get_spawndata)) {
  if ($args->get_spawndata === true) { getSpawnData($args); }
  }
  if (isset($args->get_data)) {
  if ($args->get_data === true) { getData($args); }
  }
  if (isset($args->get_optimization)) {
  if ($args->get_optimization === true) { getOptimization($args); }
  }
  if (isset($args->get_instance_data)) {
  if ($args->get_instance_data === true) { getInstanceData($args); }
  }
  if (isset($args->get_instance_names)) {
  if ($args->get_instance_names === true) { getInstanceNames($args); }
  }
}
function getInstanceData($args) {
  global $db;
  $sql_instancedata = "SELECT data, type FROM instance WHERE name = :name";
  if (isset($args->instance_name)) {
    $stmt = $db->prepare($sql_instancedata);
    $stmt->bindValue(':name', $args->instance_name, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
		$result['data'] = json_decode($result['data']);
		echo json_encode($result);
  } else {
    echo json_encode(array('status'=>'Error: no instance name?'));
    return;
  }
}
function getInstanceNames($args) {
  global $db;
  $sql_instances = "SELECT name, type FROM instance";
  $result = $db->query($sql_instances)->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($result);
}
function getSpawnData($args) {
  global $db;
  $binds = array();

  if (isset($args->spawns) || isset($args->stops)) {

    if (isset($args->spawns) && count($args->spawns) > 0) {
      $spawns_in  = str_repeat('?,', count($args->spawns) - 1) . '?';
      $binds = array_merge($binds, $args->spawns);
    }
    if (isset($args->stops) && count($args->stops) > 0) {
      $stops_in  = str_repeat('?,', count($args->stops) - 1) . '?';
      $binds = array_merge($binds, $args->stops);
    }

    if ($stops_in && $spawns_in) {
      $points_string = "(pokestop_id IN (" . $stops_in . ") OR spawn_id IN (" . $spawns_in . "))";
    } else if ($stops_in) {
      $points_string = "pokestop_id IN (" . $stops_in . ")";
    } else if ($spawns_in) {
      $points_string = "spawn_id IN (" . $spawns_in . ")";
    } else {
      echo json_encode(array('spawns' => null, 'status'=>'Error: no points!'));
      return;
    }
    if (is_numeric($args->nest_migration_timestamp) && (int)$args->nest_migration_timestamp == $args->nest_migration_timestamp) {
      $ts = $args->nest_migration_timestamp;
    } else {
      $ts = 0;
    }
    $binds[] = $ts;


    if (is_numeric($args->spawn_report_limit) && (int)$args->spawn_report_limit == $args->spawn_report_limit && (int)$args->spawn_report_limit != 0) {
      $limit = " LIMIT " . $args->spawn_report_limit;
    } else {
      $limit = '';
    }

    $sql_spawn = "SELECT pokemon_id, COUNT(pokemon_id) as count FROM pokemon WHERE " . $points_string . " AND first_seen_timestamp >= ? GROUP BY pokemon_id ORDER BY count DESC" . $limit;

    $stmt = $db->prepare($sql_spawn);
    try {
     $stmt->execute($binds);
     } catch (PDOException $e) {
      //var_dump($e);
      var_dump(array('sql_spawnpoint' => $sql_spawn));
      var_dump(array('binds_count' => count($binds), 'stop_count' => count($args->stops), 'spawn_count' => count($args->spawns)));
      var_dump($args);
    }

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  echo json_encode(array('spawns' => $result, 'sql' => $sql_spawn));
}
function getData($args) {
  global $db;
  $binds = array();
  if ($args->show_unknownpois === true) {
    $show_unknown_mod = "name IS ? AND ";
    $show_unknown_mod_sp = "despawn_sec IS NULL AND ";
    $binds[] = null;
  }
  $sql_gym = "SELECT id, lat, lon as lng, ex_raid_eligible as ex, name FROM gym WHERE " . $show_unknown_mod . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
  $stmt = $db->prepare($sql_gym);

  $stmt->execute(array_merge($binds, [$args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng]));
  $gyms = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql_pokestop = "SELECT id, lat, lon as lng, name FROM pokestop WHERE " . $show_unknown_mod . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
  $stmt = $db->prepare($sql_pokestop);

  $stmt->execute(array_merge($binds, [$args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng]));
  $stops = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sql_spawnpoint = "SELECT id, despawn_sec, lat, lon as lng FROM spawnpoint WHERE " . $show_unknown_mod_sp . "lat > ? AND lon > ? AND lat < ? AND lon < ?";
  $stmt = $db->prepare($sql_spawnpoint);

  $stmt->execute([$args->min_lat, $args->min_lng, $args->max_lat, $args->max_lng]);
  $spawns = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(array('gyms' => $gyms, 'pokestops' => $stops, 'spawnpoints' => $spawns, 'sql_gym' => $sql_gym, 'sql_pokestop' => $sql_pokestop, 'sql_spawnpoint' => $sql_spawnpoint ));
}
function getOptimization($args) {
  global $db;
  if (isset($args->points)) {
    $points = $args->points;
  } else {
    echo json_encode(array('status'=>'Error: no points'));
    return;
  }
  $best_attempt = array();
  for($x=0; $x<$args->optimization_attempts;$x++) {
    shuffle($points);
    $working_gyms = $points;
    $attempt = array();
    while(count($working_gyms) > 0) {
      $gym1 = array_pop($working_gyms);
      foreach ($working_gyms as $i => $gym2) {
        $dist = haversine($gym1, $gym2);
        if ($dist < $args->circle_size) {
          unset($working_gyms[$i]);
        }
      }
      $attempt[] = $gym1;
    }
    if(count($best_attempt) == 0 || count($attempt) < count($best_attempt)) {
      $best_attempt = $attempt;
    }
  }
  if ($args->do_tsp) {
    $working_gyms = $best_attempt;
    $index = rand(0,count($working_gyms)-1);
    $gym1 = $working_gyms[$index];
    while(count($working_gyms) > 0) {
      unset($working_gyms[$index]);
      $final_attempt[] = $gym1;
      unset($working_gyms[$index]);
      foreach ($working_gyms as $i => $gym2) {
        $dist = haversine($gym1, $gym2);
        while ($distances[$dist]) {
          $dist++;
        }
        $distances[$dist] = $gym2;
        $index = $i;
      }
      ksort($distances);
      $closest_gym = array_shift($distances);
      $gym1 = $closest_gym;
    }
    $best_attempt = $final_attempt;
  }
  echo json_encode(array('bestAttempt' => $best_attempt));
}
function haversine($gym1, $gym2) {
  $r = 6371000;
  $latFrom = ($gym1->lat * M_PI / 180);
  $lngFrom = ($gym1->lng * M_PI / 180);
  $latTo = ($gym2->lat * M_PI / 180);
  $lngTo = ($gym2->lng * M_PI / 180);
  $latDelta = $latTo - $latFrom;
  $lngDelta = $lngTo - $lngFrom;
  $a = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
      cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));
  return $a * $r;
}
?>
