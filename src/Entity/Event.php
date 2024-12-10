<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\OpenApi\Model;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_USER')",
            normalizationContext: ['groups' => ['event:read']]
        ),
        new Post(
            security: "is_granted('ROLE_USER')",
            inputFormats: ['multipart' => ['multipart/form-data']],
            denormalizationContext: ['groups' => ['event:write']],
            normalizationContext: ['groups' => ['event:read']],
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => ['type' => 'string'],
                                    'description' => ['type' => 'string'],
                                    'date' => ['type' => 'string', 'format' => 'date-time'],
                                    'location' => ['type' => 'string'],
                                    'price' => ['type' => 'number'],
                                    'user' => ['type' => 'string', 'format' => 'iri-reference'],
                                    'mediaFile' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            )
        ),
        new Get(
            security: "is_granted('ROLE_USER')",
            normalizationContext: ['groups' => ['event:read', 'event:item:read']]
        ),
        new Put(
            security: "is_granted('ROLE_USER') and object.getUser() == user",
            inputFormats: ['multipart' => ['multipart/form-data']],
            denormalizationContext: ['groups' => ['event:write']],
            normalizationContext: ['groups' => ['event:read']],
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => ['type' => 'string'],
                                    'description' => ['type' => 'string'],
                                    'date' => ['type' => 'string', 'format' => 'date-time'],
                                    'location' => ['type' => 'string'],
                                    'price' => ['type' => 'number'],
                                    'user' => ['type' => 'string', 'format' => 'iri-reference'],
                                    'mediaFile' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            )
        ),
        new Patch(
            security: "is_granted('ROLE_USER') and object.getUser() == user",
            denormalizationContext: ['groups' => ['event:write']],
            normalizationContext: ['groups' => ['event:read']]
        ),
        new Delete(
            security: "is_granted('ROLE_USER') and object.getUser() == user"
        )
    ]
)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['event:read', 'event:write'])]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    #[Groups(['event:read', 'event:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['event:read', 'event:write'])]
    private ?string $location = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    #[Groups(['event:read', 'event:write'])]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['event:read', 'event:write'])]
    private ?User $user = null;

    #[Vich\UploadableField(mapping: 'event_media', fileNameProperty: 'mediaName', size: 'mediaSize', mimeType: 'mediaMimeType')]
    #[Assert\File(
        maxSize: '100M',
        mimeTypes: [
            'image/*',
            'video/*'
        ],
        mimeTypesMessage: 'Please upload a valid image or video file'
    )]
    private ?File $mediaFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['event:read'])]
    private ?string $mediaName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['event:read'])]
    private ?int $mediaSize = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['event:read'])]
    private ?string $mediaMimeType = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['event:read', 'event:write'])]
    private ?int $placeNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $preFilledPlace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function setMediaFile(?File $mediaFile = null): void
    {
        $this->mediaFile = $mediaFile;

        if (null !== $mediaFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getMediaFile(): ?File
    {
        return $this->mediaFile;
    }

    public function getMediaName(): ?string
    {
        return $this->mediaName;
    }

    public function setMediaName(?string $mediaName): void
    {
        $this->mediaName = $mediaName;
    }

    public function getMediaSize(): ?int
    {
        return $this->mediaSize;
    }

    public function setMediaSize(?int $mediaSize): void
    {
        $this->mediaSize = $mediaSize;
    }

    public function getMediaMimeType(): ?string
    {
        return $this->mediaMimeType;
    }

    public function setMediaMimeType(?string $mediaMimeType): void
    {
        $this->mediaMimeType = $mediaMimeType;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getPlaceNumber(): ?int
    {
        return $this->placeNumber;
    }

    public function setPlaceNumber(?int $placeNumber): static
    {
        $this->placeNumber = $placeNumber;
        return $this;
    }

    public function getPreFilledPlace(): ?string
    {
        return $this->preFilledPlace;
    }

    public function setPreFilledPlace(?string $preFilledPlace): static
    {
        $this->preFilledPlace = $preFilledPlace;
        return $this;
    }
}
