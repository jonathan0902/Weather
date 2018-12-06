<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>

<form>
    <input type="text" name="location">
    <input type="submit">
</form>
<?php if ($response != false) : ?>
    <h3>Latitude: <span id="latitude"><?= $response["latitude"] . "</span> Longitude: <span id='longitude'>" . $response["longitude"]; ?></span></h3>
    <?php
    for ($i = 0; $i < count($response["daily"]["data"]); $i++) : ?>
        <h4><?= $response["daily"]["data"][$i]['summary'] ?> </h4>
        <h5><?= $response["daily"]["data"][$i]['temperatureHigh'] ?> &degC</h5>
        <?php
    endfor;
    ?>
    <div id="map" class="map"></div>
<?php else : ?>
<div class="alert alert-warning">
  <strong>Warning!</strong> Not a validated IP, could not found location.
</div>
<?php endif; ?>
