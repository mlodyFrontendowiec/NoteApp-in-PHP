<div class="show">
    <?php $note = $params['note'] ?? [] ?>
    <?php if($note) :?>
    <ul>
        <li>Id:<?php echo htmlentities($note['id'])?></li>
        <li>Tytuł:<?php echo htmlentities($note['title'])?></li>
        <li><?php echo htmlentities($note['description'])?></li>
        <li>Zapisno: <?php echo htmlentities($note['created'])?></li>
    </ul>
    <?php endif;?>
    <a href="/">
    <button>Powrót do listy notatek</button>
    </a>
</div>


