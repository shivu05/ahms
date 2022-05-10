<hr>
<h3><u>Patient information:</u></h3>
<?php
if (empty($patient_data['opd_data'])) {
    echo "No data found for thies OPD/IPD number";
} else {
    ?>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr>
                    <td colspan="3"><h4><u>OPD Information:</u></h4></td>
                </tr>
                <tr>
                    <td><b>OPD: </b><?php echo $patient_data['opd_data'][0]['OpdNo'] ?></td>
                    <td colspan="2"><b>Name: </b><?php echo $patient_data['opd_data'][0]['name'] . " " ?></td>
                </tr>
                <tr>
                    <td><b>Age: </b><?php echo $patient_data['opd_data'][0]['Age'] ?></td>
                    <td><b>Sex: </b><?php echo $patient_data['opd_data'][0]['gender'] ?></td>
                    <td><b>Place: </b><?php echo $patient_data['opd_data'][0]['city'] ?></td>
                </tr>
                <tr>
                    <td><b>Department: </b><?php echo $patient_data['opd_data'][0]['dept'] ?></td>
                    <td colspan=2><b>Date: </b><?php echo $patient_data['opd_data'][0]['entrydate'] ?></td>
                </tr>
            </table>
        </div>
    </div>
    <input type="hidden" name="pname" id="pname" value="<?php echo $patient_data['opd_data'][0]['name'] ?>"/>
    <?php
}
if (!empty($patient_data['ipd_data'])) {
    ?>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr>
                    <td colspan="3"><h4><u>IPD Information:</u></h4></td>
                </tr>
                <tr>
                    <td><b>IPD: </b><?php echo $patient_data['ipd_data'][0]['IpNo'] ?></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td><b>Admitted on:  </b><?php echo $patient_data['ipd_data'][0]['DoAdmission']; ?></td>
                    <td><b>Discharge : </b><?php echo $patient_data['ipd_data'][0]['DoDischarge'] ?></td>
                    <td><b>Doctor: </b><?php echo $patient_data['ipd_data'][0]['Doctor'] ?></td>
                </tr>
                <tr>
                    <td><b>Ward No: </b><?php echo $patient_data['ipd_data'][0]['WardNo'] ?></td>
                    <td><b>Bed No: </b><?php echo $patient_data['ipd_data'][0]['BedNo'] ?></td>
                    <td><b>No.of days: </b><?php echo $patient_data['ipd_data'][0]['NofDays'] ?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}
?>