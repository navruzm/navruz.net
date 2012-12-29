<table>
    <tbody>
        <tr>
            <td nowrap="nowrap" style="vertical-align: top;">Php Sürümü</td>
            <td><?php echo phpversion(); ?></td>
        </tr>
        <tr>
            <td nowrap="nowrap" style="vertical-align: top;">Sunucu</td>
            <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
        </tr>
        <tr>
            <td nowrap="nowrap" style="vertical-align: top;">MongoDB Versiyonu</td>
            <td><?php echo Mongo::VERSION ?></td>
        </tr>
    </tbody>
</table>