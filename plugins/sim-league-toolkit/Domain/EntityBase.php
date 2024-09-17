<?php

  namespace SLTK\Domain;

  use SLTK\Core\Constants;

  abstract class EntityBase {
    public int $id = Constants::DEFAULT_ID;
  }