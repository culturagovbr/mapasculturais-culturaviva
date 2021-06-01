<?php
    $this->bodyProperties['ng-app'] = "culturaviva";
?>
<style>
    canvas{
        width: 100px;
        height: 100px;
    }
</style>

<div id="layout" ng-controller="layoutPDFCtrl">
    <a id="download">Baixar certificado</a>
    <!-- <input type="button" id="download" value="Ver Certificado" href="#"/> -->
    <!-- <qr text="urlQRCODE" id="qrcode"></qr> -->
    <script type="text/javascript">
        var qr = document.getElementById('qrcode');
        var elementoPai = document.getElementById('layout');
        //elementoPai.removeChild(qr);

        function convertImgToBase64(callback){ //converte imagem em base64
            var img = new Image();
            img.onload = function(){
                var canvas = document.createElement('CANVAS');
                var ctx = canvas.getContext('2d');
                canvas.height = 1241;
                canvas.width = 1754;
                ctx.drawImage(this, 0, 0);
                var dataURL = canvas.toDataURL('image/png');
                callback(dataURL);
                canvas = null;
            };
            img.src = '/assets/img/certificado.png';
        }

        var button = document.getElementById("download");

        button.onclick = function(){
            convertImgToBase64(function(dataUrl){
                var doc = new jsPDF('landscape','pt',[1754,1241]);
                doc.addImage(dataUrl,'png',0,0,1754,1241);
                doc.setFontType("bold");
                doc.setFontSize(20);
                doc.text(window.name, 770, 395);
                doc.setFontSize(30);
                doc.text(window.url,570,1225);
                //qr = document.createElement('qr');
                //qr.setAttribute('text', 'teste2'); // funcao que gere pdf
                //elementoPai.appendChild(qr);


                // tentando passar tag para ser convertida

                // var dataURLQR = qr.children[0].toDataURL('image/png');
                // doc.addImage(dataURLQR,'png',667,1014,177,177);
                doc.save('Certificado.pdf');
            });
        };
    </script>
</div>
