<?php

include 'var_globale.php';
maFonction();

echo $head;
?>
        <div id="wrapper">
            <aside>

                <?php

                if ($tagId == 0){
                    echo "Vous devrez mettre un id";
                    die();
                }
                $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $tag = $lesInformations->fetch_assoc();
                ?>

                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentations</h3>
                    <p>Sur cette page vous trouverez les derniers messages comportant
                        le mot-clé :
                       <?php echo $tag['label'] ?>
                    </p>
                </section>
            </aside>
            <main>
                <?php

                $laQuestionEnSql = "SELECT 
                    posts.content,
                    posts.created,
                    users.id as author_id,
                    users.alias as author_name,
                    count(likes.id) as like_number,
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
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
                        <address>
                            <a href="wall.php?user_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?>
                        </a>
                    </address>
                        <div>
                            <p><?php echo $post['content']?></p>
                        </div>                                            
                        <footer>
                            <small>♥ <?php echo $post['like_number']?></small>
                            <a href=""><?php echo $post['taglist']?></a>
                        </footer>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
