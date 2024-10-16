<?php
include 'var_globale.php';
maFonction();

echo $head;
?>

        <div id="wrapper">
            <aside>

                <?php
               // phpinfo();
                $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                ?>

                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les messages des utilisatrices
                        auxquel est abonnée l'utilisatrice :
                        <?php echo $userId ?>
                    </p>
                </section>
            </aside>
            <main>

                <?php

                $laQuestionEnSql = "SELECT posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id as author_id,
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM followers 
                    JOIN users ON users.id=followers.followed_user_id
                    JOIN posts ON posts.user_id=users.id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE followers.following_user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                while ($post = $lesInformations->fetch_assoc())
                {
                ?>                
                <article>
                    <h3>
                        <time ><?php echo $post['created'] ?></time>
                    </h3>
                    <address><a href="wall.php?user_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?>
                    </a></address>
                    <div>
                        <p><?php echo $post['content']?></p>
                    </div>                                            
                    <footer>
                        <small>♥ <?php echo $post['like_number']?></small>
                            <a href=""><?php echo $post['taglist']?></a>
                    </footer>
                </article>
                <?php }
                ?>

            </main>
        </div>
    </body>
</html>
