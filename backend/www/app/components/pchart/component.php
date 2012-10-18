<?php

class PchartComponent extends Components {


  public function initialize($args = null) {
       $filePath = DOCUMENT_ROOT.'/www/tmp/stat/'.$args['fName'];
       $file = new SplFileInfo($filePath);
       if(time() - $file->getMTime() > 600) {

           // Standard inclusions
           include(DOCUMENT_ROOT."/system/extras/pChart/pData.class");
           include(DOCUMENT_ROOT."/system/extras/pChart/pChart.class");

           // Dataset definition
           $DataSet = new pData;
           $DataSet->AddPoint($args['values'],"Serie1");
           $DataSet->AddPoint($args['names'],"Serie2");
           $DataSet->AddAllSeries();
           $DataSet->SetAbsciseLabelSerie("Serie2");

           // Initialise the graph
           $Image = new pChart(600,500);
           $Image->drawFilledRoundedRectangle(7,7,590,490,5,240,240,240);
           $Image->drawRoundedRectangle(5,5,590,490,5,230,230,230);

           // Draw the pie chart
           $Image->setFontProperties(DOCUMENT_ROOT."/public/arial.ttf",10);
           $Image->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),280,320,170,PIE_PERCENTAGE,TRUE,60,30,7);
           $Image->drawPieLegend(60,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

           $Image->Render($filePath);

       }
  }


}