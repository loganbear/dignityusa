<?php
class CRM_Event_Badge_DUSALogo extends CRM_Event_Badge {
  function __construct() {
    parent::__construct();
    // letter
    $pw           = 215.9;  // page width
    $ph           = 279.4;  // page height
    $h            = 76.2;   // height of label
    $w            = 101.6;  // width of label
    $numAcross    = 2;      // number of labels across
    $numDown      = 3;      // number of labels down
    $this->format = array('name' => '4x3in, 6/pg Name Badges',
                          'paper-size' => 'letter',
                          'metric' => 'mm',
                          'lMargin' => ($pw - ($w * $numAcross)) / 2,
                          'tMargin' => ($ph - ($h * $numDown)) / 2,
                          'NX' => $numAcross,
                          'NY' => $numDown,
                          'SpaceX' => 0,
                          'SpaceY' => 0,
                          'width' => $w,
                          'height' => $h,
                          'font-size' => 12,
                          );
    $this->lMarginLogo = 20;
    $this->tMarginName = 20;
    //      $this->setDebug ();
  }

  public function generateLabel($participant) {
    $badgeName = NULL;
    $slogan = NULL;
    $orgName = NULL;
    $role = str_split($participant['participant_role_id']);

    //  Get Slogan of Event
    $params = array('version' => 3,
                    'sequential' => 1,
                    'event_id' => 20,
                    'return' => 'custom_32',
                    );
    $slogan = civicrm_api('Event', 'getvalue', $params);

    // Get Role - Speakers, Guests and Vendors get their employer listed
    // Attendees, Volunteers and Hosts get chapter name
    $params = array('version' => 3,
                    'sequential' => 1,
                    'participant_id' => $participant['participant_id'],
                    'return' => 'contact_id',
                    );
    
    $cId = civicrm_api('Participant', 'getvalue', $params);

    $params = array('version' => 3,
                    'sequential' => 1,
                    'id' => $cId,
                    'return' => array('nick_name', 'custom_1'),
                      );
    $result = civicrm_api('Contact', 'get', $params);
    $contact = $result['values']['0'];
    $nickName = $contact['nick_name'];
    $chapterAff = $contact['custom_1']['0'];
    if (strlen($nickName) == 0 ) {
      $firstName = $participant['first_name'];
      }
      else {
        $firstName = $nickName;
      }
      
    // Get Name of Badge 
    if ((in_array("3", $role)) ||
        (in_array("4", $role)) ||
        (in_array("5", $role)) ||
        (in_array("6", $role)) ) {
      
        $badgeName = $firstName . ' ' . $participant['last_name'];
        }
     elseif ($participant['custom_23'] == NULL) {
        $badgeName = $firstName;
      } else {
        $badgeName = $participant['custom_23'];
        }
 
    $x = $this->pdf->GetAbsX();
    $y = $this->pdf->GetY();
    $this->printBackground(TRUE);
    $this->pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'round', 'join' => 'round', 'dash' => '2,2', 'color' => array(0, 0, 200)));

    $this->pdf->SetFontSize(15);
    $this->pdf->MultiCell($this->pdf->width - $this->lMarginLogo - 5, 0, $participant['event_title'], $this->border, "L", 0, 1, $x + $this->lMarginLogo, $y);

    $this->pdf->SetFontSize(15);
    $this->pdf->SetXY($x, $y + $this->pdf->height - 20);
    $this->pdf->Cell($this->pdf->width, 0, $slogan, $this->border, 2, "C");

    $this->pdf->SetFontSize(20);
    $this->pdf->MultiCell($this->pdf->width, 10, $badgeName, $this->border, "C", 0, 1, $x, $y + $this->tMarginName);

    // If an attendee, check for chapter
    if (in_array("1", $role)) {
      if ( strlen($chapterAff)  != 0)  {
        $this->pdf->SetFontSize(15);
        $this->pdf->MultiCell($this->pdf->width, 0, $chapterAff, $this->border, "C", 0, 1, $x, $this->pdf->getY());
      }
    }
    
    
    // If Host, Speaker, Vendor or Guest, check for employer
    if ((in_array("3", $role)) ||
        (in_array("4", $role)) ||
        (in_array("5", $role)) ||
        (in_array("6", $role)) ) {
      $this->pdf->SetFontSize(15);
      $this->pdf->MultiCell($this->pdf->width, 0, $participant['current_employer'], $this->border, "C", 0, 1, $x, $this->pdf->getY());
  }
  }
}

