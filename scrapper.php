<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>
</head>
<body>
<h2><a href="index.php">Scrapper</a></h2>
<table id="example" class="display nowrap" style="width:100%">
    <thead>
        <th>URL</th>
        <th>Title</th>
        <th>Description</th>
        <th>Meta tags</th>
    </thead>
    <tbody>
        <?php
        ini_set('max_execution_time', '-1'); // unlimited
        // Web page URL 
        $urls = explode( "\r\n", $_POST['psw'] );

        foreach ($urls as $url){
            // Extract HTML using curl 
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
            
            $data = curl_exec($ch); 
            curl_close($ch); 
            
            // Load HTML to DOM object 
            $dom = new DOMDocument(); 
            @$dom->loadHTML($data); 
            
            // Parse DOM to get Title data 
            $nodes = $dom->getElementsByTagName('title'); 
            $title = $nodes->item(0)->nodeValue; 
            
            // Parse DOM to get meta data 
            $metas = $dom->getElementsByTagName('meta'); 
            
            $description = $keywords = ''; 
            for($i=0; $i<$metas->length; $i++){ 
                $meta = $metas->item($i); 
                
                if($meta->getAttribute('name') == 'description'){ 
                    $description = $meta->getAttribute('content'); 
                } 
                
                if($meta->getAttribute('name') == 'keywords'){ 
                    $keywords = $meta->getAttribute('content'); 
                } 
            } 
        ?>
        <tr>
            <td><?= $url ?></td>
            <td><?= $title ?></td>
            <td><?= $description ?></td>
            <td><?= $keywords ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>