<?php
namespace App\Entity;
use App\Repository\PaisRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=PaisRepository::class)
 * @UniqueEntity(
 *     fields={"nombre"},
 *     message="Ya existe un país con este nombre"
 * )
 */
class Pais {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(name="nombre", type="string", length=100, unique=true, nullable=false)
     * @Assert\NotBlank(message="Asegurese de ingresar el nombre del país.")
     */
    private $nombre;    
    public function getId(): ?int {
        return $this->id;
    }
    public function getNombre(): ?string {
        return $this->nombre;
    }
    public function setNombre(string $nombre): self {
        $this->nombre = strtoupper($nombre);
        return $this;
    }
    public function __toString() {
        return $this->nombre;
    }
}