 <div class="col-xs-1 col-sm-3">
 </div>
 <div class="col-xs-10 col-sm-6">
    <div class="pagenation">
        <ul class="pagination">

            <?php if($gp_data['paged'] > 1) : ?>
               <li class="prev">
                <a href="<?php echo $this->wpfunc->esc_attr( $gp_data['prev_link'] ); ?>">Prev</a>
            </li>
        <?php endif; ?>

        <?php for ($i=1; $i <= $gp_data['pages']; $i++) :  ?>

            <?php if (1 != $gp_data['pages'] &&( !($i >= $gp_data['paged']+$gp_data['range']+1 || $i <= $gp_data['paged']-$gp_data['range']-1) || $gp_data['pages'] <= $showitems ) ) :  ?>

                <?php if( $gp_data['paged'] == $i ) :  ?>

                    <li class="page-item active"><a href='' style='pointer-events: none;'><?php echo $i ?></a></li>
                    <?php else : ?>

                        <li class="page-item"><a href="<?php echo $this->wpfunc->get_pagenum_link( $i ); ?>"><?php echo $i ?></a></li>
                    <?php endif; ?>
                <?php endif; ?>

            <?php endfor; ?>

            <?php if ($gp_data['paged'] < $gp_data['pages']) : ?>
                <li class="page-item next">
                    <a href="<?php echo $this->wpfunc->esc_attr($gp_data['next_link']); ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="col-xs-1 col-sm-3">
</div>