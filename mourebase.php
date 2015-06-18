<?php

// Aplicacio per traslladar les taules de la bbdd de l'empresa a les meves taules.

require_once 'class/Core.php';
$pdoCore = Core::getInstance();



$stmt = $pdoCore->db->prepare(
        "SELECT CAS_id from CASA"
);
$stmt->execute();

$llista = $stmt->fetchAll();

// Funcio per posar el resultats null a 0.

function check($valor) {

    if ($valor == NULL) {
        $result = '0';
    } else {
        $result = $valor;
    }
    return $result;
}

// Foreach que es repeteix per cada id de casa que troba

foreach ($llista as $idcasa) {

    echo $idcasa->CAS_id;
    $infogen = [];
    $id_casa = $idcasa->CAS_id; //$_GET['id'];
    $pdoCore = Core::getInstance();
    $stmt = $pdoCore->db->prepare(
            "SELECT * FROM CASA WHERE CAS_id = :casaid "
    );
    $stmt->execute(
            array(
                ':casaid' => $id_casa
            )
    );

    $result = $stmt->fetchObject();

    foreach ($result as $camp => $casa) {
        $infogen[$camp] = $casa;
    }

    $stmt = $pdoCore->db->prepare(
            "SELECT FIT_nom, FIT_valor FROM FITXA_CASA WHERE FIT_codiCasa = :casaid "
    );
    $stmt->execute(
            array(
                ':casaid' => $id_casa
            )
    );

    $result = $stmt->fetchAll();


//Per treure el valor de idcasa

    foreach ($result as $n => $casa) {

        $infogen[$casa->FIT_nom] = $casa->FIT_valor;
    }
//var_dump($infogen);
    // $infogen es un array de la taula FITXA_CASA.

    $stmt = $pdoCore->db->prepare(
            "INSERT INTO DADES_CASA "
            . "(DAD_codiCasa, Address, GeograficCoordinates, City, Postcode) "
            . "VALUES "
            . "(:idcasa, :direccio, :Geo, :city, :post) "
    );
    $stmt->execute(
            array(
                ':idcasa' => $infogen['CAS_id'],
                ':direccio' => $infogen['Address'],
                ':Geo' => $infogen['GeographicCoordinates'],
                ':city' => $infogen['City'],
                ':post' => $infogen['Postcode'],
            )
    );
    $stmt = $pdoCore->db->prepare(
            "INSERT INTO GESTIO "
            . "(GES_codiCasa, "
            . "netetges_ges_prop, "
            . "netetges_ges_csm, "
            . "netetges_ges_col, "
            . "netetges_desp_prop, "
            . "netetges_desp_csm, "
            . "bugaderia_ges_prop , "
            . "bugaderia_ges_csm, "
            . "bugaderia_ges_col, "
            . "bugaderia_desp_prop, "
            . "bugaderia_desp_csm, "
            . "posada_ges_prop, "
            . "posada_ges_csm, "
            . "posada_ges_col, "
            . "posada_desp_prop, "
            . "posada_desp_csm, "
            . "piscina_ges_prop, "
            . "piscina_ges_csm, "
            . "piscina_ges_col, "
            . "piscina_desp_prop, "
            . "piscina_desp_csm, "
            . "jardi_ges_prop, "
            . "jardi_ges_csm, "
            . "jardi_ges_col, "
            . "jardi_desp_prop, "
            . "jardi_desp_csm, "
            . "entrades_ges_prop, "
            . "entrades_ges_csm, "
            . "entrades_ges_col, "
            . "entrades_desp_prop, "
            . "entrades_desp_csm, "
            . "sortides_ges_prop, "
            . "sortides_ges_csm, "
            . "sortides_ges_col, "
            . "sortides_desp_prop, "
            . "sortides_desp_csm, "
            . "SpecialConditionsPayment, "
            . "KeyStore, "
            . "KeyStoreCode, "
            . "UbicationKeyStore, "
            . "ChangeOfClothesAutomatic, "
            . "ChangeOfClothesOwner) "
            . "VALUES(:idcasa, "
            . ":netetges_ges_prop, "
            . ":netetgesgescsm, "
            . ":netetgesgescol, "
            . ":netetges_desp_prop, "
            . ":netetges_desp_csm, "
            . ":bugaderia_ges_prop, "
            . ":bugaderia_ges_csm, "
            . ":bugaderia_ges_col, "
            . ":bugaderia_desp_prop, "
            . ":bugaderia_desp_csm, "
            . ":posada_ges_prop, "
            . ":posada_ges_csm, "
            . ":posada_ges_col, "
            . ":posada_desp_pro, "
            . ":posada_desp_csm, "
            . ":piscina_ges_prop, "
            . ":piscina_ges_csm, "
            . ":piscina_ges_col, "
            . ":piscina_desp_prop, "
            . ":piscina_desp_csm, "
            . ":jardi_ges_prop, "
            . ":jardi_ges_csm, "
            . ":jardi_ges_col, "
            . ":jardi_desp_prop, "
            . ":jardi_desp_csm, "
            . ":entrades_ges_prop, "
            . ":entrades_ges_csm, "
            . ":entrades_ges_col, "
            . ":entrades_desp_prop, "
            . ":entrades_desp_csm, "
            . ":sortides_ges_prop, "
            . ":sortides_ges_csm, "
            . ":sortides_ges_col, "
            . ":sortides_desp_prop, "
            . ":sortides_desp_csm, "
            . ":SpecialConditionsPayment, "
            . ":KeyStore, "
            . ":KeyStoreCode, "
            . ":UbicationKeyStore, "
            . ":ChangeOfClothesAutomatic, "
            . ":ChangeOfClothesOwner ) "
    );

// Es crida la funcio check y es recullen de l'array els camps per a la taula que necesitam.

    echo check('netetges_ges_csm') . '<br>';
    $stmt->execute(
            array(
                ':idcasa' => $infogen['CAS_id'],
                ':netetges_ges_prop' => check($infogen['netetges_ges_prop']),
                ':netetgesgescsm' => check($infogen['netetges_ges_csm']),
                ':netetgesgescol' => check($infogen['netetges_ges_col']),
                ':netetges_desp_prop' => check($infogen['netetges_desp_prop']),
                ':netetges_desp_csm' => check($infogen['netetges_desp_csm']),
                ':bugaderia_ges_prop' => check($infogen['bugaderia_ges_prop']),
                ':bugaderia_ges_csm' => check($infogen['bugaderia_ges_csm']),
                ':bugaderia_ges_col' => check($infogen['bugaderia_ges_col']),
                ':bugaderia_desp_prop' => check($infogen['bugaderia_desp_prop']),
                ':bugaderia_desp_csm' => check($infogen['bugaderia_desp_csm']),
                ':posada_ges_prop' => check($infogen['posada_ges_prop']),
                ':posada_ges_csm' => check($infogen['posada_ges_csm']),
                ':posada_ges_col' => check($infogen['posada_ges_col']),
                ':posada_desp_pro' => check($infogen['posada_desp_pro']),
                ':posada_desp_csm' => check($infogen['posada_desp_csm']),
                ':piscina_ges_prop' => check($infogen['piscina_ges_prop']),
                ':piscina_ges_csm' => check($infogen['piscina_ges_csm']),
                ':piscina_ges_col' => check($infogen['piscina_ges_col']),
                ':piscina_desp_prop' => check($infogen['piscina_desp_prop']),
                ':piscina_desp_csm' => check($infogen['piscina_desp_csm']),
                ':jardi_ges_prop' => check($infogen['jardi_ges_prop']),
                ':jardi_ges_csm' => check($infogen['jardi_ges_csm']),
                ':jardi_ges_col' => check($infogen['jardi_ges_col']),
                ':jardi_desp_prop' => check($infogen['jardi_desp_prop']),
                ':jardi_desp_csm' => check($infogen['jardi_desp_csm']),
                ':entrades_ges_prop' => check($infogen['entrades_ges_prop']),
                ':entrades_ges_csm' => check($infogen['entrades_ges_csm']),
                ':entrades_ges_col' => check($infogen['entrades_ges_col']),
                ':entrades_desp_prop' => check($infogen['entrades_desp_prop']),
                ':entrades_desp_csm' => check($infogen['entrades_desp_csm']),
                ':sortides_ges_prop' => check($infogen['sortides_ges_prop']),
                ':sortides_ges_csm' => check($infogen['sortides_ges_csm']),
                ':sortides_ges_col' => check($infogen['sortides_ges_col']),
                ':sortides_desp_prop' => check($infogen['sortides_desp_prop']),
                ':sortides_desp_csm' => check($infogen['sortides_desp_csm']),
                ':SpecialConditionsPayment' => check($infogen['SpecialConditionsPayment']),
                ':KeyStore' => check($infogen['KeyStore']),
                ':KeyStoreCode' => check($infogen['KeyStoreCode']),
                ':UbicationKeyStore' => check($infogen['UbicationKeyStore']),
                ':ChangeOfClothesAutomatic' => check($infogen['ChangeOfClothesAutomatic']),
                ':ChangeOfClothesOwner' => check($infogen['ChangeOfClothesOwner'])
            )
    );

    // Es repeteix l'insert per cada nova taula creada.

    $stmt = $pdoCore->db->prepare(
            "INSERT INTO INFORMACIO_BASICA "
            . "(INF_codiCasa, MaximumCapacity, ExtraCapacity, capacitat_total, Plot, House, NumberBedrooms, NumberBathrooms, ExtraCapacityType) "
            . "VALUES "
            . "(:idcasa, :MaximumCapacity, :ExtraCapacity, :capacitat_total, :Plot, :House, :NumberBedrooms, :NumberBathrooms,:ExtraCapacityType) "
    );
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':MaximumCapacity' => check($infogen['MaximumCapacity']),
                ':ExtraCapacity' => check($infogen['ExtraCapacity']),
                ':capacitat_total' => check($infogen['capacitat_total']),
                ':Plot' => check($infogen['Plot']),
                ':House' => check($infogen['House']),
                ':NumberBedrooms' => check($infogen['NumberBedrooms']),
                ':ExtraCapacityType' => check($infogen['ExtraCapacityType']),
                ':NumberBathrooms' => check($infogen['NumberBathrooms'])
            )
    );
    $stmt = $pdoCore->db->prepare(
            "INSERT INTO ENTORN_VISTES "
            . "(ENT_codiCasa, FirstLineToBeach, NoViews, PrivacyPartial, "
            . "Mountain, SeaView, PrivacyTotal, Countryside, TownView, Village, "
            . "ForestView, ResidentialArea, CountrysideView, CoastZone, MountainView, Neighbors, GolfView) "
            . "VALUES "
            . "(:idcasa, :FirstLineToBeach, :NoViews, :PrivacyPartial, :Mountain, :SeaView, :PrivacyTotal, :Countryside, "
            . ":TownView, :Village, :ForestView, :ResidentialArea, :CountrysideView, :CoastZone, :MountainView, :Neighbors, :GolfView) "
    );
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':FirstLineToBeach' => check($infogen['FirstLineToBeach']),
                ':NoViews' => check($infogen['NoViews']),
                ':PrivacyPartial' => check($infogen['PrivacyPartial']),
                ':Mountain' => check($infogen['Mountain']),
                ':SeaView' => check($infogen['SeaView']),
                ':PrivacyTotal' => check($infogen['PrivacyTotal']),
                ':Countryside' => check($infogen['Countryside']),
                ':TownView' => check($infogen['TownView']),
                ':Village' => check($infogen['Village']),
                ':ForestView' => check($infogen['ForestView']),
                ':ResidentialArea' => check($infogen['ResidentialArea']),
                ':CountrysideView' => check($infogen['CountrysideView']),
                ':CoastZone' => check($infogen['CoastZone']),
                ':MountainView' => check($infogen['MountainView']),
                ':Neighbors' => check($infogen['Neighbors']),
                ':GolfView' => check($infogen['GolfView'])
            )
    );

    $stmt = $pdoCore->db->prepare(
            "INSERT INTO PISCINA_JARDI "
            . "(PIS_codiCasa, "
            . "PrivatePool, "
            . "SharedSwimmingPool, "
            . "FencedPool, "
            . "PoolDimensions, "
            . "PoolDepth , "
            . "AboveGroundPool, "
            . "ProgrammeLightsPool, "
            . "SwimmingPoolWithSalt, "
            . "SwimmingPoolWithChlorine, "
            . "HeatedSwimmingPool, "
            . "IntegratedJacuzzi, "
            . "SeparateJacuzzi, "
            . "ChildrenPool, "
            . "FixedBarbecue, "
            . "OutdoorShower, "
            . "MobileBarbecue, "
            . "Umbrellas, "
            . "DeckChair, "
            . "NumberOfUmbrellas, "
            . "NumberOfDeckChair, "
            . "ThereTerrace1, "
            . "ThereTerrace2, "
            . "ThereTerrace3, "
            . "ThereBalcony, "
            . "BalconyDimensions, "
            . "TerraceDimensions1, "
            . "TerraceDimensions2, "
            . "TerraceDimensions3, "
            . "FurnishedTerrace1, "
            . "FurnishedTerrace2, "
            . "FurnishedTerrace3, "
            . "FurnishedPorche1, "
            . "FurnishedPorche2, "
            . "FurnishedPorche3, "
            . "FurnishedBalcony, "
            . "PorchDimensions1, "
            . "PorchDimensions2, "
            . "PorchDimensions3, "
            . "TableTennis, "
            . "IndoorPool, "
            . "Floor, "
            . "FloorSurface, "
            . "Grass, "
            . "GrassSurface, "
            . "VegetableGarden, "
            . "Fence, "
            . "FruitTree, "
            . "GardenAcces, "
            . "FloorCommunity, "
            . "FloorSurfaceCommunity, "
            . "GrassCommunity, "
            . "GrassSurfaceCommunity, "
            . "FenceCommunity, "
            . "GardenNotes) "
            . "VALUES(:idcasa, "
            . ":PrivatePool, "
            . ":SharedSwimmingPool, "
            . ":FencedPool, "
            . ":PoolDimensions, "
            . ":PoolDepth, "
            . ":AboveGroundPool, "
            . ":ProgrammeLightsPool, "
            . ":SwimmingPoolWithSalt, "
            . ":SwimmingPoolWithChlorine, "
            . ":HeatedSwimmingPool, "
            . ":IntegratedJacuzzi, "
            . ":SeparateJacuzzi, "
            . ":ChildrenPool, "
            . ":FixedBarbecue, "
            . ":OutdoorShower, "
            . ":MobileBarbecue, "
            . ":Umbrellas, "
            . ":DeckChair, "
            . ":NumberOfUmbrellas, "
            . ":NumberOfDeckChair, "
            . ":ThereTerrace1, "
            . ":ThereTerrace2, "
            . ":ThereTerrace3, "
            . ":ThereBalcony, "
            . ":BalconyDimensions, "
            . ":TerraceDimensions1, "
            . ":TerraceDimensions2, "
            . ":TerraceDimensions3, "
            . ":FurnishedTerrace1, "
            . ":FurnishedTerrace2, "
            . ":FurnishedTerrace3, "
            . ":FurnishedPorche1, "
            . ":FurnishedPorche2, "
            . ":FurnishedPorche3, "
            . ":FurnishedBalcony, "
            . ":PorchDimensions1, "
            . ":PorchDimensions2, "
            . ":PorchDimensions3, "
            . ":TableTennis, "
            . ":IndoorPool, "
            . ":Floor, "
            . ":FloorSurface, "
            . ":Grass, "
            . ":GrassSurface, "
            . ":VegetableGarden, "
            . ":Fence, "
            . ":FruitTree, "
            . ":GardenAcces, "
            . ":FloorCommunity, "
            . ":FloorSurfaceCommunity, "
            . ":GrassCommunity, "
            . ":GrassSurfaceCommunity, "
            . ":FenceCommunity, "
            . ":GardenNotes) ");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':PrivatePool' => check($infogen['PrivatePool']),
                ':SharedSwimmingPool' => check($infogen['SharedSwimmingPool']),
                ':FencedPool' => check($infogen['FencedPool']),
                ':PoolDimensions' => check($infogen['PoolDimensions']),
                ':PoolDepth' => check($infogen['PoolDepth']),
                ':AboveGroundPool' => check($infogen['AboveGroundPool']),
                ':ProgrammeLightsPool' => check($infogen['ProgrammeLightsPool']),
                ':SwimmingPoolWithSalt' => check($infogen['SwimmingPoolWithSalt']),
                ':SwimmingPoolWithChlorine' => check($infogen['SwimmingPoolWithChlorine']),
                ':HeatedSwimmingPool' => check($infogen['HeatedSwimmingPool']),
                ':IntegratedJacuzzi' => check($infogen['IntegratedJacuzzi']),
                ':SeparateJacuzzi' => check($infogen['SeparateJacuzzi']),
                ':ChildrenPool' => check($infogen['ChildrenPool']),
                ':FixedBarbecue' => check($infogen['FixedBarbecue']),
                ':OutdoorShower' => check($infogen['OutdoorShower']),
                ':MobileBarbecue' => check($infogen['MobileBarbecue']),
                ':Umbrellas' => check($infogen['Umbrellas']),
                ':DeckChair' => check($infogen['DeckChair']),
                ':NumberOfUmbrellas' => check($infogen['NumberOfUmbrellas']),
                ':NumberOfDeckChair' => check($infogen['NumberOfDeckChair']),
                ':ThereTerrace1' => check($infogen['ThereTerrace1']),
                ':ThereTerrace2' => check($infogen['ThereTerrace2']),
                ':ThereTerrace3' => check($infogen['ThereTerrace3']),
                ':ThereBalcony' => check($infogen['ThereBalcony']),
                ':BalconyDimensions' => check($infogen['BalconyDimensions']),
                ':TerraceDimensions1' => check($infogen['TerraceDimensions1']),
                ':TerraceDimensions2' => check($infogen['TerraceDimensions2']),
                ':TerraceDimensions3' => check($infogen['TerraceDimensions3']),
                ':FurnishedTerrace1' => check($infogen['FurnishedTerrace1']),
                ':FurnishedTerrace2' => check($infogen['FurnishedTerrace2']),
                ':FurnishedTerrace3' => check($infogen['FurnishedTerrace3']),
                ':FurnishedPorche1' => check($infogen['FurnishedPorche1']),
                ':FurnishedPorche2' => check($infogen['FurnishedPorche2']),
                ':FurnishedPorche3' => check($infogen['FurnishedPorche3']),
                ':FurnishedBalcony' => check($infogen['FurnishedBalcony']),
                ':PorchDimensions1' => check($infogen['PorchDimensions1']),
                ':PorchDimensions2' => check($infogen['PorchDimensions2']),
                ':PorchDimensions3' => check($infogen['PorchDimensions3']),
                ':TableTennis' => check($infogen['TableTennis']),
                ':IndoorPool' => check($infogen['IndoorPool']),
                ':Floor' => check($infogen['Floor']),
                ':FloorSurface' => check($infogen['FloorSurface']),
                ':Grass' => check($infogen['Grass']),
                ':GrassSurface' => check($infogen['GrassSurface']),
                ':VegetableGarden' => check($infogen['VegetableGarden']),
                ':Fence' => check($infogen['Fence']),
                ':FruitTree' => check($infogen['FruitTree']),
                ':GardenAcces' => check($infogen['GardenAcces']),
                ':FloorCommunity' => check($infogen['FloorCommunity']),
                ':FloorSurfaceCommunity' => check($infogen['FloorSurfaceCommunity']),
                ':GrassCommunity' => check($infogen['GrassCommunity']),
                ':GrassSurfaceCommunity' => check($infogen['GrassSurfaceCommunity']),
                ':FenceCommunity' => check($infogen['FenceCommunity']),
                ':GardenNotes' => check($infogen['GardenNotes'])
            )
    );
    $stmt = $pdoCore->db->prepare(
            "INSERT INTO EQUIPAMENT "
            . "(EQU_codiCasa, "
            . "AirConditioning, "
            . "AirConditioningLocation, "
            . "AirConditioningNumber, "
            . "AirConditioningNotes, "
            . "GasStove , "
            . "WoodStove, "
            . "CentralDieselHeating, "
            . "CentralGasHeating, "
            . "ElectricRadiators, "
            . "NumberOfElectricRadiators, "
            . "Chimney, "
            . "NumberOfFireplaces, "
            . "Fans, "
            . "NumberOfFans, "
            . "ContractedPower, "
            . "UnderfloorHeating, "
            . "Wifi, "
            . "WifiCode, "
            . "ElectricalBox, "
            . "Television, "
            . "SatelliteTelevision, "
            . "EnglishChannel, "
            . "GermanChannel, "
            . "ItalianChannel, "
            . "FrenchChannel, "
            . "DutchChannel, "
            . "RussianChannel, "
            . "ArabianChannel, "
            . "DvdPlayer, "
            . "DVD, "
            . "CD, "
            . "CdPlayer, "
            . "ChildrensGames, "
            . "Films, "
            . "VideoGames, "
            . "TypeOfVideoGames, "
            . "Books, "
            . "TypeOfBooks, "
            . "Strongbox, "
            . "AlarmSystem) "
            . "VALUES(:idcasa, "
            . ":AirConditioning, "
            . ":AirConditioningLocation, "
            . ":AirConditioningNumber, "
            . ":AirConditioningNotes, "
            . ":GasStove, "
            . ":WoodStove, "
            . ":CentralDieselHeating, "
            . ":CentralGasHeating, "
            . ":ElectricRadiators, "
            . ":NumberOfElectricRadiators, "
            . ":Chimney, "
            . ":NumberOfFireplaces, "
            . ":Fans, "
            . ":NumberOfFans, "
            . ":ContractedPower, "
            . ":UnderfloorHeating, "
            . ":Wifi, "
            . ":WifiCode, "
            . ":ElectricalBox, "
            . ":Television, "
            . ":SatelliteTelevision, "
            . ":EnglishChannel, "
            . ":GermanChannel, "
            . ":ItalianChannel, "
            . ":FrenchChannel, "
            . ":DutchChannel, "
            . ":RussianChannel, "
            . ":ArabianChannel, "
            . ":DvdPlayer, "
            . ":DVD, "
            . ":CD, "
            . ":CdPlayer, "
            . ":ChildrensGames, "
            . ":Films, "
            . ":VideoGames, "
            . ":TypeOfVideoGames, "
            . ":Books, "
            . ":TypeOfBooks, "
            . ":Strongbox, "
            . ":AlarmSystem)");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':AirConditioning' => check($infogen['AirConditioning']),
                ':AirConditioningLocation' => check($infogen['AirConditioningLocation']),
                ':AirConditioningNumber' => check($infogen['AirConditioningNumber']),
                ':AirConditioningNotes' => check($infogen['AirConditioningNotes']),
                ':GasStove' => check($infogen['GasStove']),
                ':WoodStove' => check($infogen['WoodStove']),
                ':CentralDieselHeating' => check($infogen['CentralDieselHeating']),
                ':CentralGasHeating' => check($infogen['CentralGasHeating']),
                ':ElectricRadiators' => check($infogen['ElectricRadiators']),
                ':NumberOfElectricRadiators' => check($infogen['NumberOfElectricRadiators']),
                ':Chimney' => check($infogen['Chimney']),
                ':NumberOfFireplaces' => check($infogen['NumberOfFireplaces']),
                ':Fans' => check($infogen['Fans']),
                ':NumberOfFans' => check($infogen['NumberOfFans']),
                ':ContractedPower' => check($infogen['ContractedPower']),
                ':UnderfloorHeating' => check($infogen['UnderfloorHeating']),
                ':Wifi' => check($infogen['Wifi']),
                ':WifiCode' => check($infogen['WifiCode']),
                ':ElectricalBox' => check($infogen['ElectricalBox']),
                ':Television' => check($infogen['Television']),
                ':SatelliteTelevision' => check($infogen['SatelliteTelevision']),
                ':EnglishChannel' => check($infogen['EnglishChannel']),
                ':GermanChannel' => check($infogen['GermanChannel']),
                ':ItalianChannel' => check($infogen['ItalianChannel']),
                ':FrenchChannel' => check($infogen['FrenchChannel']),
                ':DutchChannel' => check($infogen['DutchChannel ']),
                ':RussianChannel' => check($infogen['RussianChannel']),
                ':ArabianChannel' => check($infogen['ArabianChannel']),
                ':DvdPlayer' => check($infogen['DvdPlayer']),
                ':DVD' => check($infogen['DVD']),
                ':CD' => check($infogen['CD']),
                ':CdPlayer' => check($infogen['CdPlayer']),
                ':ChildrensGames' => check($infogen['ChildrensGames']),
                ':Films' => check($infogen['Films']),
                ':VideoGames' => check($infogen['VideoGames']),
                ':TypeOfVideoGames' => check($infogen['TypeOfVideoGames']),
                ':Books' => check($infogen['Books']),
                ':TypeOfBooks' => check($infogen['TypeOfBooks']),
                ':Strongbox' => check($infogen['Strongbox']),
                ':AlarmSystem' => check($infogen['AlarmSystem'])
            )
    );

    // Taula BUGADERIA

    $stmt = $pdoCore->db->prepare(
            "INSERT INTO BUGADERIA "
            . "(BUG_codiCasa, "
            . "WashingMachine, "
            . "Dryer, "
            . "Iron, "
            . "Vacuum, "
            . "LitersElectricBoiler , "
            . "Laundry, "
            . "OtherFurniture, "
            . "OtherTypeOfFurniture) "
            . "VALUES(:idcasa, "
            . ":WashingMachine, "
            . ":Dryer, "
            . ":Iron, "
            . ":Vacuum, "
            . ":LitersElectricBoiler, "
            . ":Laundry, "
            . ":OtherFurniture, "
            . ":OtherTypeOfFurniture)");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':WashingMachine' => check($infogen['WashingMachine']),
                ':Dryer' => check($infogen['Dryer']),
                ':Iron' => check($infogen['Iron']),
                ':Vacuum' => check($infogen['Vacuum']),
                ':LitersElectricBoiler' => check($infogen['LitersElectricBoiler']),
                ':Laundry' => check($infogen['Laundry']),
                ':OtherFurniture' => check($infogen['OtherFurniture']),
                ':OtherTypeOfFurniture' => check($infogen['OtherTypeOfFurniture'])
            )
    );
    $stmt = $pdoCore->db->prepare(
            "INSERT INTO GENERAL "
            . "(GEN_codiCasa, "
            . "OtherFurniture, "
            . "OtherTypeOfFurniture, "
            . "kitchenNotes)"
            . "VALUES(:idcasa, "
            . ":OtherFurniture, "
            . ":OtherTypeOfFurniture, "
            . ":kitchenNotes);
");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':OtherFurniture' => check($infogen['OtherFurniture']),
                ':OtherTypeOfFurniture' => check($infogen['OtherTypeOfFurniture']),
                ':kitchenNotes' => check($infogen['kitchenNotes'])
            )
    );

    // Taula INFORMACIO_ADICIONAL

    $stmt = $pdoCore->db->prepare(
            "INSERT INTO INFORMACIO_ADICIONAL "
            . "(INF_codiCasa, "
            . "YearOfConstruction, "
            . "YearOfRemodel, "
            . "FloorsHouse, "
            . "AllowPets, "
            . "PetsOnRequest, "
            . "NoAllowPets, "
            . "SolarEnergy, "
            . "NumberOfNeighbors, "
            . "FloorHouse, "
            . "Parking, "
            . "ParkingSpaces, "
            . "Garage, "
            . "GarageSpaces, "
            . "ParkingGarage, "
            . "ParkingGarageSlots, "
            . "Lift, "
            . "ParkingNotes)"
            . "VALUES(:idcasa, "
            . ":YearOfConstruction, "
            . ":YearOfRemodel, "
            . ":FloorsHouse, "
            . ":AllowPets, "
            . ":PetsOnRequest, "
            . ":NoAllowPets, "
            . ":SolarEnergy, "
            . ":NumberOfNeighbors, "
            . ":FloorHouse , "
            . ":Parking, "
            . ":ParkingSpaces, "
            . ":Garage, "
            . ":GarageSpaces, "
            . ":ParkingGarage, "
            . ":ParkingGarageSlots, "
            . ":Lift, "
            . ":Noise, "
            . ":NoiseType, "
            . ":ParkingNotes);
");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':YearOfConstruction' => check($infogen['YearOfConstruction']),
                ':YearOfRemodel' => check($infogen['YearOfRemodel']),
                ':FloorsHouse' => check($infogen['FloorsHouse']),
                ':AllowPets' => check($infogen['AllowPets']),
                ':PetsOnRequest' => check($infogen['PetsOnRequest']),
                ':NoAllowPets' => check($infogen['NoAllowPets']),
                ':SolarEnergy' => check($infogen['SolarEnergy']),
                ':NumberOfNeighbors' => check($infogen['NumberOfNeighbors']),
                ':FloorHouse' => check($infogen['FloorHouse']),
                ':Parking' => check($infogen['Parking']),
                ':ParkingSpaces' => check($infogen['ParkingSpaces']),
                ':Garage' => check($infogen['Garage']),
                ':GarageSpaces' => check($infogen['GarageSpaces']),
                ':ParkingGarage' => check($infogen['ParkingGarage']),
                ':ParkingGarageSlots' => check($infogen['ParkingGarageSlots']),
                ':Lift' => check($infogen['Lift']),
                ':Noise' => check($infogen['Noise']),
                ':NoiseType' => check($infogen['NoiseType']),
                ':ParkingNotes' => check($infogen['ParkingNotes'])
            )
    );

    // Taula DISTANCIES

    $stmt = $pdoCore->db->prepare(
            "INSERT INTO DISTANCIES "
            . "(DIS_codiCasa, "
            . "NumberDistanceToBank, "
            . "NameDistanceToBank, "
            . "NumberDistanceToSupermarket, "
            . "NameDistanceToSupermarket, "
            . "NumberDistanceToBeach, "
            . "NameDistanceToBeach, "
            . "NumberDistanceToAirport, "
            . "NameDistanceToAirport, "
            . "NumberDistanceToGolf, "
            . "NameDistanceToGolf, "
            . "NumberDistanceToVillage, "
            . "NameDistanceToVillage, "
            . "NumberDistanceToTrain, "
            . "NameDistanceToTrain, "
            . "NumberDistanceToBus, "
            . "NameDistanceToBus, "
            . "NumberDistanceToFerry, "
            . "NameDistanceToFerry, "
            . "NumberDistanceToHospital, "
            . "NameDistanceToHospital, "
            . "NumberDistanceToPharmacy, "
            . "NameDistanceToPharmacy, "
            . "NumberDistanceToRestaurant, "
            . "NameDistanceToRestaurant, "
            . "DistanceNotes)"
            . "VALUES(:idcasa, "
            . ":NumberDistanceToBank, "
            . ":NameDistanceToBank, "
            . ":NumberDistanceToSupermarket, "
            . ":NameDistanceToSupermarket, "
            . ":NumberDistanceToBeach, "
            . ":NameDistanceToBeach, "
            . ":NumberDistanceToAirport, "
            . ":NameDistanceToAirport, "
            . ":NumberDistanceToGolf , "
            . ":NameDistanceToGolf, "
            . ":NumberDistanceToVillage, "
            . ":NameDistanceToVillage, "
            . ":NumberDistanceToTrain, "
            . ":NameDistanceToTrain, "
            . ":NumberDistanceToBus, "
            . ":NameDistanceToBus, "
            . ":NumberDistanceToFerry, "
            . ":NameDistanceToFerry, "
            . ":NumberDistanceToHospital, "
            . ":NameDistanceToHospital, "
            . ":NumberDistanceToPharmacy, "
            . ":NameDistanceToPharmacy, "
            . ":NumberDistanceToRestaurant, "
            . ":NameDistanceToRestaurant, "
            . ":DistanceNotes);
");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':NumberDistanceToBank' => check($infogen['NumberDistanceToBank']),
                ':NameDistanceToBank' => check($infogen['NameDistanceToBank']),
                ':NumberDistanceToSupermarket' => check($infogen['NumberDistanceToSupermarket']),
                ':NameDistanceToSupermarket' => check($infogen['NameDistanceToSupermarket']),
                ':NumberDistanceToBeach' => check($infogen['NumberDistanceToBeach']),
                ':NameDistanceToBeach' => check($infogen['NameDistanceToBeach']),
                ':NumberDistanceToAirport' => check($infogen['NumberDistanceToAirport']),
                ':NameDistanceToAirport' => check($infogen['NameDistanceToAirport']),
                ':NumberDistanceToGolf' => check($infogen['NumberDistanceToGolf']),
                ':NameDistanceToGolf' => check($infogen['NameDistanceToGolf']),
                ':NumberDistanceToVillage' => check($infogen['NumberDistanceToVillage']),
                ':NameDistanceToVillage' => check($infogen['NameDistanceToVillage']),
                ':NumberDistanceToTrain' => check($infogen['NumberDistanceToTrain']),
                ':NameDistanceToTrain' => check($infogen['NameDistanceToTrain']),
                ':NumberDistanceToBus' => check($infogen['NumberDistanceToBus']),
                ':NameDistanceToBus' => check($infogen['NameDistanceToBus']),
                ':NumberDistanceToFerry' => check($infogen['NumberDistanceToFerry']),
                ':NameDistanceToFerry' => check($infogen['NameDistanceToFerry']),
                ':NumberDistanceToHospital' => check($infogen['NumberDistanceToHospital']),
                ':NameDistanceToHospital' => check($infogen['NameDistanceToHospital']),
                ':NumberDistanceToPharmacy' => check($infogen['NumberDistanceToPharmacy']),
                ':NameDistanceToPharmacy' => check($infogen['NameDistanceToPharmacy']),
                ':NumberDistanceToRestaurant' => check($infogen['NumberDistanceToRestaurant']),
                ':NameDistanceToRestaurant' => check($infogen['NameDistanceToRestaurant']),
                ':DistanceNotes' => check($infogen['DistanceNotes'])
            )
    );

    // Taula CUINA


    $stmt = $pdoCore->db->prepare(
            "INSERT INTO CUINA "
            . "(CUI_codiCasa, "
            . "SeparateKitchen, "
            . "LivingDiningRoom, "
            . "KitchenDiningRoom, "
            . "DimensionsKitchen, "
            . "HighChairKitchen , "
            . "BarWithStoolsKitchen, "
            . "BlenderKitchen, "
            . "ToasterKitchen, "
            . "ExtractorKitchen, "
            . "MicrowaveKitchen, "
            . "DishwasherKitchen, "
            . "SqueezerKitchen, "
            . "FridgeWithFreezerKitchen, "
            . "FreezerKitchen, "
            . "ElectricFurnaceKitchen, "
            . "FridgeKitchen, "
            . "KettleKitchen, "
            . "GasKitchen, "
            . "VitroceramicKitchen, "
            . "GasFurnaceKitchen, "
            . "InductionKitchen, "
            . "ElectricCoffeeMakerKitchen, "
            . "ItalianCoffeeMakerKitchen, "
            . "TablesKitchen, "
            . "NumberOfTablesKitchen, "
            . "ChairsKitchen, "
            . "NumberOfChairsKitchen, "
            . "LarderKitchen) "
            . "VALUES(:idcasa, "
            . ":SeparateKitchen, "
            . ":LivingDiningRoom, "
            . ":KitchenDiningRoom, "
            . ":DimensionsKitchen, "
            . ":HighChairKitchen, "
            . ":BarWithStoolsKitchen, "
            . ":BlenderKitchen, "
            . ":ToasterKitchen, "
            . ":ExtractorKitchen, "
            . ":MicrowaveKitchen, "
            . ":DishwasherKitchen, "
            . ":SqueezerKitchen, "
            . ":FridgeWithFreezerKitchen, "
            . ":FreezerKitchen, "
            . ":ElectricFurnaceKitchen, "
            . ":FridgeKitchen, "
            . ":KettleKitchen, "
            . ":GasKitchen, "
            . ":VitroceramicKitchen, "
            . ":GasFurnaceKitchen, "
            . ":InductionKitchen, "
            . ":ElectricCoffeeMakerKitchen, "
            . ":ItalianCoffeeMakerKitchen, "
            . ":TablesKitchen, "
            . ":NumberOfTablesKitchen, "
            . ":ChairsKitchen, "
            . ":NumberOfChairsKitchen, "
            . ":LarderKitchen)");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':SeparateKitchen' => check($infogen['SeparateKitchen']),
                ':LivingDiningRoom' => check($infogen['LivingDiningRoom']),
                ':KitchenDiningRoom' => check($infogen['KitchenDiningRoom']),
                ':DimensionsKitchen' => check($infogen['DimensionsKitchen1']),
                ':HighChairKitchen' => check($infogen['HighChairKitchen1']),
                ':BarWithStoolsKitchen' => check($infogen['BarWithStoolsKitchen1']),
                ':BlenderKitchen' => check($infogen['BlenderKitchen1']),
                ':ToasterKitchen' => check($infogen['ToasterKitchen1']),
                ':ExtractorKitchen' => check($infogen['ExtractorKitchen1']),
                ':MicrowaveKitchen' => check($infogen['MicrowaveKitchen1']),
                ':DishwasherKitchen' => check($infogen['DishwasherKitchen1']),
                ':SqueezerKitchen' => check($infogen['SqueezerKitchen1']),
                ':FridgeWithFreezerKitchen' => check($infogen['FridgeWithFreezerKitchen1']),
                ':FreezerKitchen' => check($infogen['FreezerKitchen1']),
                ':ElectricFurnaceKitchen' => check($infogen['ElectricFurnaceKitchen1']),
                ':FridgeKitchen' => check($infogen['FridgeKitchen1']),
                ':KettleKitchen' => check($infogen['KettleKitchen1']),
                ':GasKitchen' => check($infogen['GasKitchen1']),
                ':VitroceramicKitchen' => check($infogen['VitroceramicKitchen1']),
                ':GasFurnaceKitchen' => check($infogen['GasFurnaceKitchen1']),
                ':InductionKitchen' => check($infogen['InductionKitchen1']),
                ':ElectricCoffeeMakerKitchen' => check($infogen['ElectricCoffeeMakerKitchen1']),
                ':ItalianCoffeeMakerKitchen' => check($infogen['ItalianCoffeeMakerKitchen1']),
                ':TablesKitchen' => check($infogen['TablesKitchen1']),
                ':NumberOfTablesKitchen' => check($infogen['NumberOfTablesKitchen1']),
                ':ChairsKitchen' => check($infogen['ChairsKitchen1']),
                ':NumberOfChairsKitchen' => check($infogen['NumberOfChairsKitchen1']),
                ':LarderKitchen' => check($infogen['LarderKitchen1'])
            )
    );

    // S'ha de fer un for perque repeteixi l'insert per cada camp que acabi amb un numero.

    for ($x = 2; $x < 10; $x++) {
        if (isset($infogen['DimensionsKitchen' . $x . '']) && $infogen['DimensionsKitchen' . $x . ''] != '') {

            $stmt = $pdoCore->db->prepare(
                    "INSERT INTO CUINA "
                    . "(CUI_codiCasa, "
                    . "SeparateKitchen, "
                    . "LivingDiningRoom, "
                    . "KitchenDiningRoom, "
                    . "DimensionsKitchen, "
                    . "HighChairKitchen , "
                    . "BarWithStoolsKitchen, "
                    . "BlenderKitchen, "
                    . "ToasterKitchen, "
                    . "ExtractorKitchen, "
                    . "MicrowaveKitchen, "
                    . "DishwasherKitchen, "
                    . "SqueezerKitchen, "
                    . "FridgeWithFreezerKitchen, "
                    . "FreezerKitchen, "
                    . "ElectricFurnaceKitchen, "
                    . "FridgeKitchen, "
                    . "KettleKitchen, "
                    . "GasKitchen, "
                    . "VitroceramicKitchen, "
                    . "GasFurnaceKitchen, "
                    . "InductionKitchen, "
                    . "ElectricCoffeeMakerKitchen, "
                    . "ItalianCoffeeMakerKitchen, "
                    . "TablesKitchen, "
                    . "NumberOfTablesKitchen, "
                    . "ChairsKitchen, "
                    . "NumberOfChairsKitchen, "
                    . "LarderKitchen) "
                    . "VALUES(:idcasa, "
                    . ":SeparateKitchen, "
                    . ":LivingDiningRoom, "
                    . ":KitchenDiningRoom, "
                    . ":DimensionsKitchen, "
                    . ":HighChairKitchen, "
                    . ":BarWithStoolsKitchen, "
                    . ":BlenderKitchen, "
                    . ":ToasterKitchen, "
                    . ":ExtractorKitchen, "
                    . ":MicrowaveKitchen, "
                    . ":DishwasherKitchen, "
                    . ":SqueezerKitchen, "
                    . ":FridgeWithFreezerKitchen, "
                    . ":FreezerKitchen, "
                    . ":ElectricFurnaceKitchen, "
                    . ":FridgeKitchen, "
                    . ":KettleKitchen, "
                    . ":GasKitchen, "
                    . ":VitroceramicKitchen, "
                    . ":GasFurnaceKitchen, "
                    . ":InductionKitchen, "
                    . ":ElectricCoffeeMakerKitchen, "
                    . ":ItalianCoffeeMakerKitchen, "
                    . ":TablesKitchen, "
                    . ":NumberOfTablesKitchen, "
                    . ":ChairsKitchen, "
                    . ":NumberOfChairsKitchen, "
                    . ":LarderKitchen)");
            $stmt->execute(
                    array(':idcasa' => $infogen['CAS_id'],
                        ':SeparateKitchen' => check($infogen['SeparateKitchen' . $x . '']),
                        ':LivingDiningRoom' => check($infogen['LivingDiningRoom' . $x . '']),
                        ':KitchenDiningRoom' => check($infogen['KitchenDiningRoom' . $x . '']),
                        ':DimensionsKitchen' => check($infogen['DimensionsKitchen' . $x . '']),
                        ':HighChairKitchen' => check($infogen['HighChairKitchen' . $x . '']),
                        ':BarWithStoolsKitchen' => check($infogen['BarWithStoolsKitchen' . $x . '']),
                        ':BlenderKitchen' => check($infogen['BlenderKitchen' . $x . '']),
                        ':ToasterKitchen' => check($infogen['ToasterKitchen' . $x . '']),
                        ':ExtractorKitchen' => check($infogen['ExtractorKitchen' . $x . '']),
                        ':MicrowaveKitchen' => check($infogen['MicrowaveKitchen' . $x . '']),
                        ':DishwasherKitchen' => check($infogen['DishwasherKitchen' . $x . '']),
                        ':SqueezerKitchen' => check($infogen['SqueezerKitchen' . $x . '']),
                        ':FridgeWithFreezerKitchen' => check($infogen['FridgeWithFreezerKitchen' . $x . '']),
                        ':FreezerKitchen' => check($infogen['FreezerKitchen' . $x . '']),
                        ':ElectricFurnaceKitchen' => check($infogen['ElectricFurnaceKitchen' . $x . '']),
                        ':FridgeKitchen' => check($infogen['FridgeKitchen' . $x . '']),
                        ':KettleKitchen' => check($infogen['KettleKitchen' . $x . '']),
                        ':GasKitchen' => check($infogen['GasKitchen' . $x . '']),
                        ':VitroceramicKitchen' => check($infogen['VitroceramicKitchen' . $x . '']),
                        ':GasFurnaceKitchen' => check($infogen['GasFurnaceKitchen' . $x . '']),
                        ':InductionKitchen' => check($infogen['InductionKitchen' . $x . '']),
                        ':ElectricCoffeeMakerKitchen' => check($infogen['ElectricCoffeeMakerKitchen' . $x . '']),
                        ':ItalianCoffeeMakerKitchen' => check($infogen['ItalianCoffeeMakerKitchen' . $x . '']),
                        ':TablesKitchen' => check($infogen['TablesKitchen' . $x . '']),
                        ':NumberOfTablesKitchen' => check($infogen['NumberOfTablesKitchen' . $x . '']),
                        ':ChairsKitchen' => check($infogen['ChairsKitchen' . $x . '']),
                        ':NumberOfChairsKitchen' => check($infogen['NumberOfChairsKitchen' . $x . '']),
                        ':LarderKitchen' => check($infogen['LarderKitchen' . $x . ''])
                    )
            );
        }
    }

    // Taula SALA


    $stmt = $pdoCore->db->prepare(
            "INSERT INTO SALA "
            . "(SAL_codiCasa, "
            . "DimensionsHall, "
            . "SofasHall, "
            . "ArmChairHall, "
            . "SofaSeatsHall, "
            . "ArmChairSeatsHall , "
            . "NumberOfSofasHall, "
            . "NumberOfArmChairHall, "
            . "SofaBedHall, "
            . "SofaBedSeatsHall, "
            . "TypeOfSofaBedHall, "
            . "NumberOfSofasBedHall, "
            . "AirConditioningHall) "
            . "VALUES(:idcasa, "
            . ":DimensionsHall, "
            . ":SofasHall, "
            . ":ArmChairHall, "
            . ":SofaSeatsHall, "
            . ":ArmChairSeatsHall, "
            . ":NumberOfSofasHall, "
            . ":NumberOfArmChairHall, "
            . ":SofaBedHall, "
            . ":SofaBedSeatsHall, "
            . ":TypeOfSofaBedHall, "
            . ":HallDiningroom, "
            . ":NumberOfSofasBedHall, "
            . ":AirConditioningHall)");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':DimensionsHall' => check($infogen['DimensionsHall1']),
                ':SofasHall' => check($infogen['SofasHall1']),
                ':ArmChairHall' => check($infogen['ArmChairHall1']),
                ':SofaSeatsHall' => check($infogen['SofaSeatsHall1']),
                ':ArmChairSeatsHall' => check($infogen['ArmChairSeatsHall1']),
                ':NumberOfSofasHall' => check($infogen['NumberOfSofasHall1']),
                ':NumberOfArmChairHall' => check($infogen['NumberOfArmChairHall1']),
                ':HallDiningroom' => check($infogen['HallDiningroom']),
                ':SofaBedHall' => check($infogen['SofaBedHall1']),
                ':SofaBedSeatsHall' => check($infogen['SofaBedSeatsHall1']),
                ':TypeOfSofaBedHall' => check($infogen['TypeOfSofaBedHall1']),
                ':NumberOfSofasBedHall' => check($infogen['NumberOfSofasBedHall1']),
                ':AirConditioningHall' => check($infogen['AirConditioningHall1'])
            )
    );

    for ($x = 2; $x < 11; $x++) {
        if (isset($infogen['DimensionsHall' . $x . '']) && $infogen['DimensionsHall' . $x . ''] != '') {

            $stmt = $pdoCore->db->prepare(
                    "INSERT INTO SALA "
                    . "(SAL_codiCasa, "
                    . "DimensionsHall, "
                    . "SofasHall, "
                    . "ArmChairHall, "
                    . "SofaSeatsHall, "
                    . "ArmChairSeatsHall , "
                    . "NumberOfSofasHall, "
                    . "NumberOfArmChairHall, "
                    . "SofaBedHall, "
                    . "SofaBedSeatsHall, "
                    . "TypeOfSofaBedHall, "
                    . "NumberOfSofasBedHall, "
                    . "AirConditioningHall) "
                    . "VALUES(:idcasa, "
                    . ":DimensionsHall, "
                    . ":SofasHall, "
                    . ":ArmChairHall, "
                    . ":SofaSeatsHall, "
                    . ":ArmChairSeatsHall, "
                    . ":HallDiningroom, "
                    . ":NumberOfSofasHall, "
                    . ":NumberOfArmChairHall, "
                    . ":SofaBedHall, "
                    . ":SofaBedSeatsHall, "
                    . ":TypeOfSofaBedHall, "
                    . ":NumberOfSofasBedHall, "
                    . ":AirConditioningHall)");
            $stmt->execute(
                    array(':idcasa' => $infogen['CAS_id'],
                        ':DimensionsHall' => check($infogen['DimensionsHall' . $x . '']),
                        ':SofasHall' => check($infogen['SofasHall' . $x . '']),
                        ':ArmChairHall' => check($infogen['ArmChairHall' . $x . '']),
                        ':SofaSeatsHall' => check($infogen['SofaSeatsHall' . $x . '']),
                        ':ArmChairSeatsHall' => check($infogen['ArmChairSeatsHall' . $x . '']),
                        ':NumberOfSofasHall' => check($infogen['NumberOfSofasHall' . $x . '']),
                        ':HallDiningroom' => check($infogen['HallDiningroom' . $x . '']),
                        ':NumberOfArmChairHall' => check($infogen['NumberOfArmChairHall' . $x . '']),
                        ':SofaBedHall' => check($infogen['SofaBedHall' . $x . '']),
                        ':SofaBedSeatsHall' => check($infogen['SofaBedSeatsHall' . $x . '']),
                        ':TypeOfSofaBedHall' => check($infogen['TypeOfSofaBedHall' . $x . '']),
                        ':NumberOfSofasBedHall' => check($infogen['NumberOfSofasBedHall' . $x . '']),
                        ':AirConditioningHall' => check($infogen['AirConditioningHall' . $x . ''])
                    )
            );
        }
    }

    // Taula MENJADOR

    $stmt = $pdoCore->db->prepare(
            "INSERT INTO MENJADOR "
            . "(MEN_codiCasa, "
            . "DimensionsLivingroom, "
            . "SideTableDiningRoom, "
            . "SideDiningTableSeatsDining, "
            . "SeatsDining) "
            . "VALUES(:idcasa, "
            . ":DimensionsLivingroom, "
            . ":SideTableDiningRoom, "
            . ":SideDiningTableSeatsDining, "
            . ":SeatsDining)");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':DimensionsLivingroom' => check($infogen['DimensionsLivingroom1']),
                ':SideTableDiningRoom' => check($infogen['SideTableDiningRoom1']),
                ':SideDiningTableSeatsDining' => check($infogen['SideDiningTableSeatsDining1']),
                ':SeatsDining' => check($infogen['SeatsDining1'])
            )
    );

    for ($x = 2; $x < 11; $x++) {
        if (isset($infogen['DimensionsLivingroom' . $x . '']) && $infogen['DimensionsLivingroom' . $x . ''] != '') {

            $stmt = $pdoCore->db->prepare(
                    "INSERT INTO MENJADOR "
                    . "(MEN_codiCasa, "
                    . "DimensionsLivingroom, "
                    . "SideTableDiningRoom, "
                    . "SideDiningTableSeatsDining, "
                    . "SeatsDining) "
                    . "VALUES(:idcasa, "
                    . ":DimensionsLivingroom, "
                    . ":SideTableDiningRoom, "
                    . ":SideDiningTableSeatsDining, "
                    . ":SeatsDining)");
            $stmt->execute(
                    array(':idcasa' => $infogen['CAS_id'],
                        ':DimensionsLivingroom' => check($infogen['DimensionsLivingroom' . $x . '']),
                        ':SideTableDiningRoom' => check($infogen['SideTableDiningRoom' . $x . '']),
                        ':SideDiningTableSeatsDining' => check($infogen['SideDiningTableSeatsDining' . $x . '']),
                        ':SeatsDining' => check($infogen['SeatsDining' . $x . ''])
                    )
            );
        }
    }

    // Taula DORMITORI


    $stmt = $pdoCore->db->prepare(
            "INSERT INTO DORMITORI "
            . "(DOR_codiCasa, "
            . "BedroomDimensions, "
            . "FloorBedroom, "
            . "AttachedBedroom, "
            . "DoubleBedBedroom, "
            . "WidthDoubleBedBedroom, "
            . "HeightDoubleBedBedroom, "
            . "NumberOfDoubleBedsBedroom, "
            . "EnsuiteBedroom, "
            . "SingleBedNumberBedroom, "
            . "WidthSingleBedBedroom, "
            . "HeightSingleBedBedroom, "
            . "NumberOfSingleBedsBedroom, "
            . "AuxiliaryBedBedroom, "
            . "TrundleBedBedroom, "
            . "WidthTrundleBedBedroom, "
            . "HeightTrundleBedBedroom, "
            . "CapacityTrundleBed, "
            . "CotBedroom, "
            . "BunkBedBedroom, "
            . "WidthBunkBedBedroom, "
            . "HeightBunkBedBedroom, "
            . "BalconyBedroom, "
            . "WindowlessBedroom, "
            . "ACBedRoom, "
            . "FanBedRoom, "
            . "DieselCentralHeatingBedRoom, "
            . "ChimneyBedRoom, "
            . "RadiatorsBedRoom, "
            . "GasBedRoom, "
            . "TypeOfCotBedRoom, "
            . "WardrobeBedroom, "
            . "ExitToTerraceBedroom, "
            . "HallwayBedroom, "
            . "ViewsBedroom, "
            . "NotesBedroom) "
            . "VALUES(:idcasa, "
            . ":BedroomDimensions, "
            . ":FloorBedroom, "
            . ":AttachedBedroom, "
            . ":DoubleBedBedroom, "
            . ":WidthDoubleBedBedroom, "
            . ":HeightDoubleBedBedroom, "
            . ":NumberOfDoubleBedsBedroom, "
            . ":EnsuiteBedroom, "
            . ":SingleBedNumberBedroom, "
            . ":WidthSingleBedBedroom, "
            . ":HeightSingleBedBedroom, "
            . ":NumberOfSingleBedsBedroom, "
            . ":AuxiliaryBedBedroom, "
            . ":TrundleBedBedroom, "
            . ":WidthTrundleBedBedroom, "
            . ":HeightTrundleBedBedroom, "
            . ":CapacityTrundleBed, "
            . ":CotBedroom, "
            . ":BunkBedBedroom, "
            . ":WidthBunkBedBedroom, "
            . ":HeightBunkBedBedroom, "
            . ":BalconyBedroom, "
            . ":WindowlessBedroom, "
            . ":ACBedRoom, "
            . ":FanBedRoom, "
            . ":DieselCentralHeatingBedRoom, "
            . ":ChimneyBedRoom, "
            . ":RadiatorsBedRoom, "
            . ":GasBedRoom, "
            . ":TypeOfCotBedRoom, "
            . ":WardrobeBedroom, "
            . ":ExitToTerraceBedroom, "
            . ":HallwayBedroom, "
            . ":ViewsBedroom, "
            . ":NotesBedroom)");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':BedroomDimensions' => check($infogen['BedroomDimensions1']),
                ':FloorBedroom' => check($infogen['FloorBedroom1']),
                ':AttachedBedroom' => check($infogen['AttachedBedroom1']),
                ':DoubleBedBedroom' => check($infogen['DoubleBedBedroom1']),
                ':WidthDoubleBedBedroom' => check($infogen['WidthDoubleBedBedroom1']),
                ':HeightDoubleBedBedroom' => check($infogen['HeightDoubleBedBedroom1']),
                ':NumberOfDoubleBedsBedroom' => check($infogen['NumberOfDoubleBedsBedroom1']),
                ':EnsuiteBedroom' => check($infogen['EnsuiteBedroom1']),
                ':SingleBedNumberBedroom' => check($infogen['SingleBedNumberBedroom1']),
                ':WidthSingleBedBedroom' => check($infogen['WidthSingleBedBedroom1']),
                ':HeightSingleBedBedroom' => check($infogen['HeightSingleBedBedroom1']),
                ':NumberOfSingleBedsBedroom' => check($infogen['NumberOfSingleBedsBedroom1']),
                ':AuxiliaryBedBedroom' => check($infogen['AuxiliaryBedBedroom1']),
                ':TrundleBedBedroom' => check($infogen['TrundleBedBedroom1']),
                ':WidthTrundleBedBedroom' => check($infogen['WidthTrundleBedBedroom1']),
                ':HeightTrundleBedBedroom' => check($infogen['HeightTrundleBedBedroom1']),
                ':CapacityTrundleBed' => check($infogen['CapacityTrundleBed1']),
                ':CotBedroom' => check($infogen['CotBedroom1']),
                ':BunkBedBedroom' => check($infogen['BunkBedBedroom1']),
                ':WidthBunkBedBedroom' => check($infogen['WidthBunkBedBedroom1']),
                ':HeightBunkBedBedroom' => check($infogen['HeightBunkBedBedroom1']),
                ':BalconyBedroom' => check($infogen['BalconyBedroom1']),
                ':WindowlessBedroom' => check($infogen['WindowlessBedroom1']),
                ':ACBedRoom' => check($infogen['ACBedRoom1']),
                ':FanBedRoom' => check($infogen['FanBedRoom1']),
                ':DieselCentralHeatingBedRoom' => check($infogen['DieselCentralHeatingBedRoom1']),
                ':ChimneyBedRoom' => check($infogen['ChimneyBedRoom1']),
                ':RadiatorsBedRoom' => check($infogen['RadiatorsBedRoom1']),
                ':GasBedRoom' => check($infogen['GasBedRoom1']),
                ':TypeOfCotBedRoom' => check($infogen['TypeOfCotBedRoom1']),
                ':WardrobeBedroom' => check($infogen['WardrobeBedroom1']),
                ':ExitToTerraceBedroom' => check($infogen['ExitToTerraceBedroom1']),
                ':HallwayBedroom' => check($infogen['HallwayBedroom1']),
                ':ViewsBedroom' => check($infogen['ViewsBedroom1']),
                ':NotesBedroom' => check($infogen['NotesBedroom1'])
            )
    );

    for ($x = 2; $x < 11; $x++) {
        if (isset($infogen['BedroomDimensions' . $x . '']) && $infogen['BedroomDimensions' . $x . ''] != '') {
            $stmt = $pdoCore->db->prepare(
                    "INSERT INTO DORMITORI "
                    . "(DOR_codiCasa, "
                    . "BedroomDimensions, "
                    . "FloorBedroom, "
                    . "AttachedBedroom, "
                    . "DoubleBedBedroom, "
                    . "WidthDoubleBedBedroom, "
                    . "HeightDoubleBedBedroom, "
                    . "NumberOfDoubleBedsBedroom, "
                    . "EnsuiteBedroom, "
                    . "SingleBedNumberBedroom, "
                    . "WidthSingleBedBedroom, "
                    . "HeightSingleBedBedroom, "
                    . "NumberOfSingleBedsBedroom, "
                    . "AuxiliaryBedBedroom, "
                    . "TrundleBedBedroom, "
                    . "WidthTrundleBedBedroom, "
                    . "HeightTrundleBedBedroom, "
                    . "CapacityTrundleBed, "
                    . "CotBedroom, "
                    . "BunkBedBedroom, "
                    . "WidthBunkBedBedroom, "
                    . "HeightBunkBedBedroom, "
                    . "BalconyBedroom, "
                    . "WindowlessBedroom, "
                    . "ACBedRoom, "
                    . "FanBedRoom, "
                    . "DieselCentralHeatingBedRoom, "
                    . "ChimneyBedRoom, "
                    . "RadiatorsBedRoom, "
                    . "GasBedRoom, "
                    . "TypeOfCotBedRoom, "
                    . "WardrobeBedroom, "
                    . "ExitToTerraceBedroom, "
                    . "HallwayBedroom, "
                    . "ViewsBedroom, "
                    . "NotesBedroom) "
                    . "VALUES(:idcasa, "
                    . ":BedroomDimensions, "
                    . ":FloorBedroom, "
                    . ":AttachedBedroom, "
                    . ":DoubleBedBedroom, "
                    . ":WidthDoubleBedBedroom, "
                    . ":HeightDoubleBedBedroom, "
                    . ":NumberOfDoubleBedsBedroom, "
                    . ":EnsuiteBedroom, "
                    . ":SingleBedNumberBedroom, "
                    . ":WidthSingleBedBedroom, "
                    . ":HeightSingleBedBedroom, "
                    . ":NumberOfSingleBedsBedroom, "
                    . ":AuxiliaryBedBedroom, "
                    . ":TrundleBedBedroom, "
                    . ":WidthTrundleBedBedroom, "
                    . ":HeightTrundleBedBedroom, "
                    . ":CapacityTrundleBed, "
                    . ":CotBedroom, "
                    . ":BunkBedBedroom, "
                    . ":WidthBunkBedBedroom, "
                    . ":HeightBunkBedBedroom, "
                    . ":BalconyBedroom, "
                    . ":WindowlessBedroom, "
                    . ":ACBedRoom, "
                    . ":FanBedRoom, "
                    . ":DieselCentralHeatingBedRoom, "
                    . ":ChimneyBedRoom, "
                    . ":RadiatorsBedRoom, "
                    . ":GasBedRoom, "
                    . ":TypeOfCotBedRoom, "
                    . ":WardrobeBedroom, "
                    . ":ExitToTerraceBedroom, "
                    . ":HallwayBedroom, "
                    . ":ViewsBedroom, "
                    . ":NotesBedroom)");
            $stmt->execute(
                    array(':idcasa' => $infogen['CAS_id'],
                        ':BedroomDimensions' => check($infogen['BedroomDimensions' . $x . '']),
                        ':FloorBedroom' => check($infogen['FloorBedroom' . $x . '']),
                        ':AttachedBedroom' => check($infogen['AttachedBedroom' . $x . '']),
                        ':DoubleBedBedroom' => check($infogen['DoubleBedBedroom' . $x . '']),
                        ':WidthDoubleBedBedroom' => check($infogen['WidthDoubleBedBedroom' . $x . '']),
                        ':HeightDoubleBedBedroom' => check($infogen['HeightDoubleBedBedroom' . $x . '']),
                        ':NumberOfDoubleBedsBedroom' => check($infogen['NumberOfDoubleBedsBedroom' . $x . '']),
                        ':EnsuiteBedroom' => check($infogen['EnsuiteBedroom' . $x . '']),
                        ':SingleBedNumberBedroom' => check($infogen['SingleBedNumberBedroom' . $x . '']),
                        ':WidthSingleBedBedroom' => check($infogen['WidthSingleBedBedroom' . $x . '']),
                        ':HeightSingleBedBedroom' => check($infogen['HeightSingleBedBedroom' . $x . '']),
                        ':NumberOfSingleBedsBedroom' => check($infogen['NumberOfSingleBedsBedroom' . $x . '']),
                        ':AuxiliaryBedBedroom' => check($infogen['AuxiliaryBedBedroom' . $x . '']),
                        ':TrundleBedBedroom' => check($infogen['TrundleBedBedroom' . $x . '']),
                        ':WidthTrundleBedBedroom' => check($infogen['WidthTrundleBedBedroom' . $x . '']),
                        ':HeightTrundleBedBedroom' => check($infogen['HeightTrundleBedBedroom' . $x . '']),
                        ':CapacityTrundleBed' => check($infogen['CapacityTrundleBed' . $x . '']),
                        ':CotBedroom' => check($infogen['CotBedroom' . $x . '']),
                        ':BunkBedBedroom' => check($infogen['BunkBedBedroom' . $x . '']),
                        ':WidthBunkBedBedroom' => check($infogen['WidthBunkBedBedroom' . $x . '']),
                        ':HeightBunkBedBedroom' => check($infogen['HeightBunkBedBedroom' . $x . '']),
                        ':BalconyBedroom' => check($infogen['BalconyBedroom' . $x . '']),
                        ':WindowlessBedroom' => check($infogen['WindowlessBedroom' . $x . '']),
                        ':ACBedRoom' => check($infogen['ACBedRoom' . $x . '']),
                        ':FanBedRoom' => check($infogen['FanBedRoom' . $x . '']),
                        ':DieselCentralHeatingBedRoom' => check($infogen['DieselCentralHeatingBedRoom' . $x . '']),
                        ':ChimneyBedRoom' => check($infogen['ChimneyBedRoom' . $x . '']),
                        ':RadiatorsBedRoom' => check($infogen['RadiatorsBedRoom' . $x . '']),
                        ':GasBedRoom' => check($infogen['GasBedRoom' . $x . '']),
                        ':TypeOfCotBedRoom' => check($infogen['TypeOfCotBedRoom' . $x . '']),
                        ':WardrobeBedroom' => check($infogen['WardrobeBedroom' . $x . '']),
                        ':ExitToTerraceBedroom' => check($infogen['ExitToTerraceBedroom' . $x . '']),
                        ':HallwayBedroom' => check($infogen['HallwayBedroom' . $x . '']),
                        ':ViewsBedroom' => check($infogen['ViewsBedroom' . $x . '']),
                        ':NotesBedroom' => check($infogen['NotesBedroom' . $x . ''])
                    )
            );
        }
    }

    // Taula BANY


    $stmt = $pdoCore->db->prepare(
            "INSERT INTO BANY "
            . "(BAN_codiCasa, "
            . "BathroomDimensions, "
            . "FloorBathroom, "
            . "AttachedBathroom, "
            . "CompleteBathroom, "
            . "SinkBathroom, "
            . "ToiletBathRoom, "
            . "BidetBathroom, "
            . "ShowerBathroom, "
            . "TypeOfShoweBathRoom, "
            . "BathBathroom, "
            . "TypeOfBathBathRoom, "
            . "EnsuiteBathroom, "
            . "JacuzziBathroom, "
            . "SaunaBathroom, "
            . "HairdryerBathRoom, "
            . "OutsideBathroom, "
            . "HeatingBathRoom) "
            . "VALUES(:idcasa, "
            . ":BathroomDimensions, "
            . ":FloorBathroom, "
            . ":AttachedBathroom, "
            . ":CompleteBathroom, "
            . ":SinkBathroom, "
            . ":ToiletBathRoom, "
            . ":BidetBathroom, "
            . ":ShowerBathroom, "
            . ":TypeOfShoweBathRoom, "
            . ":BathBathroom, "
            . ":TypeOfBathBathRoom, "
            . ":EnsuiteBathroom, "
            . ":JacuzziBathroom, "
            . ":SaunaBathroom, "
            . ":HairdryerBathRoom, "
            . ":OutsideBathroom, "
            . ":EnsuiteBathroomLocation, "
            . ":HeatingBathRoom)");
    $stmt->execute(
            array(':idcasa' => $infogen['CAS_id'],
                ':BathroomDimensions' => check($infogen['BathroomDimensions1']),
                ':FloorBathroom' => check($infogen['FloorBathroom1']),
                ':AttachedBathroom' => check($infogen['AttachedBathroom1']),
                ':CompleteBathroom' => check($infogen['CompleteBathroom1']),
                ':SinkBathroom' => check($infogen['SinkBathroom1']),
                ':ToiletBathRoom' => check($infogen['ToiletBathRoom1']),
                ':BidetBathroom' => check($infogen['BidetBathroom1']),
                ':ShowerBathroom' => check($infogen['ShowerBathroom1']),
                ':TypeOfShoweBathRoom' => check($infogen['TypeOfShoweBathRoom1']),
                ':BathBathroom' => check($infogen['BathBathroom1']),
                ':TypeOfBathBathRoom' => check($infogen['TypeOfBathBathRoom1']),
                ':EnsuiteBathroom' => check($infogen['EnsuiteBathroom1']),
                ':JacuzziBathroom' => check($infogen['JacuzziBathroom1']),
                ':SaunaBathroom' => check($infogen['SaunaBathroom1']),
                ':HairdryerBathRoom' => check($infogen['HairdryerBathRoom1']),
                ':OutsideBathroom' => check($infogen['OutsideBathroom1']),
                ':EnsuiteBathroomLocation' => check($infogen['EnsuiteBathroomLocation']),
                ':HeatingBathRoom' => check($infogen['HeatingBathRoom1'])
            )
    );

    for ($x = 2; $x < 11; $x++) {
        if (isset($infogen['BathroomDimensions' . $x . '']) && $infogen['BathroomDimensions' . $x . ''] != '') {

            $stmt = $pdoCore->db->prepare(
                    "INSERT INTO BANY "
                    . "(BAN_codiCasa, "
                    . "BathroomDimensions, "
                    . "FloorBathroom, "
                    . "AttachedBathroom, "
                    . "CompleteBathroom, "
                    . "SinkBathroom, "
                    . "ToiletBathRoom, "
                    . "BidetBathroom, "
                    . "ShowerBathroom, "
                    . "TypeOfShoweBathRoom, "
                    . "BathBathroom, "
                    . "TypeOfBathBathRoom, "
                    . "EnsuiteBathroom, "
                    . "JacuzziBathroom, "
                    . "SaunaBathroom, "
                    . "HairdryerBathRoom, "
                    . "OutsideBathroom, "
                    . "HeatingBathRoom) "
                    . "VALUES(:idcasa, "
                    . ":BathroomDimensions, "
                    . ":FloorBathroom, "
                    . ":AttachedBathroom, "
                    . ":CompleteBathroom, "
                    . ":SinkBathroom, "
                    . ":ToiletBathRoom, "
                    . ":BidetBathroom, "
                    . ":ShowerBathroom, "
                    . ":TypeOfShoweBathRoom, "
                    . ":BathBathroom, "
                    . ":TypeOfBathBathRoom, "
                    . ":EnsuiteBathroom, "
                    . ":JacuzziBathroom, "
                    . ":SaunaBathroom, "
                    . ":HairdryerBathRoom, "
                    . ":OutsideBathroom, "
                    . ":EnsuiteBathroomLocation, "
                    . ":HeatingBathRoom)");
            $stmt->execute(
                    array(':idcasa' => $infogen['CAS_id'],
                        ':BathroomDimensions' => check($infogen['BathroomDimensions' . $x . '']),
                        ':FloorBathroom' => check($infogen['FloorBathroom' . $x . '']),
                        ':AttachedBathroom' => check($infogen['AttachedBathroom' . $x . '']),
                        ':CompleteBathroom' => check($infogen['CompleteBathroom' . $x . '']),
                        ':SinkBathroom' => check($infogen['SinkBathroom' . $x . '']),
                        ':ToiletBathRoom' => check($infogen['ToiletBathRoom' . $x . '']),
                        ':BidetBathroom' => check($infogen['BidetBathroom' . $x . '']),
                        ':ShowerBathroom' => check($infogen['ShowerBathroom' . $x . '']),
                        ':TypeOfShoweBathRoom' => check($infogen['TypeOfShoweBathRoom' . $x . '']),
                        ':BathBathroom' => check($infogen['BathBathroom' . $x . '']),
                        ':TypeOfBathBathRoom' => check($infogen['TypeOfBathBathRoom' . $x . '']),
                        ':EnsuiteBathroom' => check($infogen['EnsuiteBathroom' . $x . '']),
                        ':JacuzziBathroom' => check($infogen['JacuzziBathroom' . $x . '']),
                        ':SaunaBathroom' => check($infogen['SaunaBathroom' . $x . '']),
                        ':HairdryerBathRoom' => check($infogen['HairdryerBathRoom' . $x . '']),
                        ':OutsideBathroom' => check($infogen['OutsideBathroom' . $x . '']),
                        ':EnsuiteBathroomLocation' => check($infogen['EnsuiteBathroomLocation' . $x . '']),
                        ':HeatingBathRoom' => check($infogen['HeatingBathRoom' . $x . ''])
                    )
            );
        }
    }
} // fi foreach cases.