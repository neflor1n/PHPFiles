<?php
echo "<h1 style='text-align: -webkit-center;'>Ajafunktsioonid</h1>";
echo "<div id='kuupaev'>";
echo "Täna on: ".date('d.m.Y')."<br>";
date_default_timezone_set('Europe/Tallinn');
echo "<strong>";
echo "Tänane Tallina kuupäev ja kellaaeg on: ".date('d.m.Y G:i', time())."<br>";
echo "date('d.m.Y G:i', time())";
echo "<br>";
echo "d - kuupaev ja 1-31";
echo "<br>";
echo "m - kuu 1-12";
echo "<br>";
echo "Y - aasta 1970-2100";
echo "<br>";
echo "G - tunni 0-23";
echo "<br>";
echo "h - tunni 1-12";
echo "<br>";
echo "i - minutid 0-59";
echo "<br>";
echo "s - sekundid 0-59";
echo "<br>";
echo "</strong>";
echo "</div>";
?>

<div id="hooaeg">
    <h2>
        Väljasta vastavalt hooajale pilt (kevad/suvi/talv/sügis)
    </h2>
    <?php
            $today = new DateTime();
            echo "Täna on: ".$today->format("d-m.Y");
            echo "<br>";
            //hooaja punktid 
            $spring = new DateTime('20 March');
            $summer = new DateTime('21 June');
            $autumn = new DateTime('22 September');
            $winter = new DateTime('23 December');

            switch (true) {
                //spring
                case ($today >= $spring && $today < $summer):
                    echo "Kevad";
                    echo "<br>";
                    $pildi_aadress = 'content/img/kevad.jpeg';
                    break;
                
                //summer
                case ($today >= $summer && $today < $autumn):
                    echo "Suvi";
                    echo "<br>";
                    $pildi_aadress = 'content/img/suvi.jpeg';
                    break;

                //autumn
                case ( $today >= $autumn && $today < $winter):
                    echo "Sügis";
                    echo "<br>";
                    $pildi_aadress = 'content/img/sugis.jpeg';
                    break;

                //winter
                case ($today >= $winter && $today < $spring):
                    echo "Talv";
                    echo "<br>";
                    $pildi_aadress = 'content/img/talv.jpeg';
                    break;
            }
    ?>
    <img src="<?php echo $pildi_aadress; ?>" alt="hooaja pilt">
</div>


<div id="koolivaheaeg">
    <h2>
        Mitu päeva on koolivaheajani 23.12.2024 
    </h2>
    <?php
        $kdate = date_create_from_format('d.m.Y', '23.12.2024');
        $date = date_create();
        $diff = date_diff(baseObject: $kdate, targetObject: $date);
        //echo "Jääb: ".$diff->format('%a')." päeva";
        //echo "<br>";
        echo "Jääb: ".$diff->days." päeva";
    ?>
        
</div>

<div id="minuSunnipaev">
    <h2>
        Mitu päeva on minu sünnipäevani 25.10.2025
    </h2>
    <?php
        $kdate = date_create_from_format('d.m.Y', '25.10.2025');
        $date = date_create();
        $diff = date_diff(baseObject: $kdate, targetObject: $date);
        //echo "Jääb: ".$diff->format('%a')." päeva";
        //echo "<br>";
        echo "Jääb: ".$diff->days." päeva";
    ?>

</div>



<div id="vanus">
    <h2>Kasutaja vanuse leidmine</h2>
    <form method="post" action="">
        Sisesta oma sünnikuupäev
        <input type="date" name="synd" placeholder="dd.mm.yyyy">
        <input type="submit" value="OK">
    </form>
    <?php
    if (isset($_REQUEST["synd"])){
        if(empty ($_REQUEST["synd"])){

            echo "sisesta oma Sünnipäeva kuupäev";
        }
        else{
            $sdate=date_create($_REQUEST["synd"]);
            $date=date_create();
            $interval=date_diff($sdate,$date);
            echo "Sa oled ".$interval->format("%y")." aastat vana";
        }
    }
    ?>
</div>


<div id="massiviKuu">
    <h2>Massivi abil näidata kuu nimega tänases kuupäevas.</h2>
    <?php
    $kuud = array(1 => 'jaanuar', 'veebruar','märts','aprill','mai','juuni','juuli','august','september','oktoober','november','detsember');

    $paev = date('d');
    $year = date('Y');
    $kuu = $kuud[date('m')];
    echo "Praegu on ".$paev.".".$kuu.".".$year.".";
    ?>
</div>