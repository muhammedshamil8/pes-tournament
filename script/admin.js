function getClubInfo($club_id) {
     $clubInfo = [
         1 => ["P-S-G", "images/psg.webp"],
         2 => ["FC Barcelona", "images/Fcb.webp"],
     ];
 
     if (isset($clubInfo[$club_id])) {
         return $clubInfo[$club_id];
     }
 
     return ["Unknown Club", "images/logo.webp"];
 }