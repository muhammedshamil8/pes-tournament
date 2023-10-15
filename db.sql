
 <!-- 
CREATE TABLE teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    tournament_id INT,
    team_name VARCHAR(255),
    CONSTRAINT fk_tournament_id
        FOREIGN KEY (tournament_id)
        REFERENCES tournaments (tournament_id)
        ON DELETE CASCADE
);


CREATE TABLE matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    tournament_id INT,
    team1_id INT,
    team2_id INT,
    match_date DATE,
    CONSTRAINT fk_tournament_id
        FOREIGN KEY (tournament_id)
        REFERENCES tournaments (tournament_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_team1_id
        FOREIGN KEY (team1_id)
        REFERENCES teams (team_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_team2_id
        FOREIGN KEY (team2_id)
        REFERENCES teams (team_id)
        ON DELETE CASCADE
);


     -->