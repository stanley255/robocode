CREATE VIEW ROBOCODE.V_POSTS AS
  SELECT P.id, P.date, P.text, P.pinned, U.name, U.surname, U.id "user_id"
  FROM posts P
  JOIN users U
  ON U.id = P.FK_USER_ID
  ORDER BY P.date DESC, P.id DESC;
